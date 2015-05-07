/**
 * Core Design Lock Article plugin for Joomla! 2.5
 * @author		Daniel Rataj, <info@greatjoomla.com>
 * @package		Joomla
 * @subpackage	Content
 * @category	Plugin
 * @version		2.5.x.2.0.5
 * @copyright	Copyright (C) 2007 - 2013 Great Joomla!, http://www.greatjoomla.com
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL 3
 * 
 * This file is part of Great Joomla! extension.   
 * This extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if (typeof jQuery === 'function') {
	(function($) {
		
		$.fn.cdlockarticle = function(options) {
			var defaults = {
					livepath : '',
                    name : 'cdlockarticle',
                    layout : 'default',
					language : {
						PLG_CONTENT_CDLOCKARTICLE_PASSWORD : 'Password:',
						PLG_CONTENT_CDLOCKARTICLE_PASSWORD2 : 'Confirm password:',
						PLG_CONTENT_CDLOCKARTICLE_ERROR_PASSWORD_DO_NOT_MATCH : 'The passwords you entered do not match.',
						PLG_CONTENT_CDLOCKARTICLE_HEADER : 'Header (optional):',
						PLG_CONTENT_CDLOCKARTICLE_PASSWORD_TO_UNLOCK : 'Password to unlock this article:',
						PLG_CONTENT_CDLOCKARTICLE_LOCK_ARTICLE : 'Lock Article',
						PLG_CONTENT_CDLOCKARTICLE_UNLOCK_ARTICLE : 'Unlock Article'
					}
			},
			opts = $.extend(defaults, options),
            $this = undefined;

			return this.each(function() {
				$this = $(this),
				preload_image = new Image(16, 16),
				preload_image.src = opts.livepath + "/plugins/content/" + opts.name + "/tmpl/" + opts.layout + "/images/loading.gif";

                layout();
                initEvents();
			});

            /**
             * Check password
             * @param form
             * @param event
             * @returns {boolean}
             */
            function submit_checkPassword(form, event)
            {
                event.preventDefault();

                var input_password = $('input[type="password"]', form);

                if ($.trim(input_password.val()) == '' || $.trim(input_password.val()).length > 255)
                {
                    // prevent emtpy password or password max lenght value (255)
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: form.serialize(),
                    beforeSend: function()
                    {
                        loading();
                    },
                    success: function(response)
                    {
                        if (response.status === 'error')
                        {
                            alert(response.content);
                            input_password.val('');
                            return false;
                        }

                        // success, refresh page
                        window.location.reload();
                        return true;
                    },
                    complete: function()
                    {
                        loading();
                    }
                });
            }
            /**
             * Unlock source
             * @param form
             * @param event
             * @returns {boolean}
             */
            function submit_unlock(form, event)
            {
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: form.serialize(),
                    beforeSend: function()
                    {
                        $('button:submit', form).button('disable');
                    },
                    success: function(response)
                    {
                        if (response.status === 'error')
                        {
                            alert(response.content);
                            return false;
                        }

                        var current_data = $('button:submit', form).data(opts.name + 'UiButton');
                        current_data.icons.primary = 'ui-icon-locked';
                        $('button:submit', form).data(opts.name + 'UiButton', current_data);

                        $('button:submit', form).button('option', 'label', opts.language.PLG_CONTENT_CDLOCKARTICLE_LOCK_ARTICLE);

                        var event_data = form.data(opts.name + 'Event')[0];
                        event_data.name = 'lock';
                        form.data(opts.name + 'Event', event_data);
                        $('input[name="' + opts.name + '_task"]', form).val('post_lock');

                        layout();

                    },
                    complete: function()
                    {
                        $('button:submit', form).button('enable');
                    }
                });
                return true;
            };

            /**
             * Lock source
             * @param form
             * @param event
             * @returns {boolean}
             */
            function submit_lock(form, event)
            {
                event.preventDefault();

                var password = $.trim(prompt(opts.language.PLG_CONTENT_CDLOCKARTICLE_PASSWORD, ''));
                if (!password) return false;

                var password2 = $.trim(prompt(opts.language.PLG_CONTENT_CDLOCKARTICLE_PASSWORD2, ''));
                if (!password2) return false;

                if (password != password2)
                {
                    alert(opts.language.PLG_CONTENT_CDLOCKARTICLE_ERROR_PASSWORD_DO_NOT_MATCH);
                    return false;
                }

                var headertext = prompt(opts.language.PLG_CONTENT_CDLOCKARTICLE_HEADER, '');

                if (headertext)
                {
                    $('input[name="' + opts.name + '_headertext"]', form).val(headertext);
                }

                $('input[name="' + opts.name + '_password"]', form).val(password);

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: form.serialize(),
                    beforeSend: function()
                    {
                        $('button:submit', form).button('disable');
                    },
                    success: function(response)
                    {
                        if (response.status === 'error')
                        {
                            alert(response.content);
                            return false;
                        }

                        var current_data = $('button:submit', form).data(opts.name + 'UiButton');
                        current_data.icons.primary = 'ui-icon-unlocked';
                        $('button:submit', form).data(opts.name + 'UiButton', current_data);

                        $('button:submit', form).button('option', 'label', opts.language.PLG_CONTENT_CDLOCKARTICLE_UNLOCK_ARTICLE);

                        var event_data = form.data(opts.name + 'Event')[0];
                        event_data.name = 'unlock';

                        form.data(opts.name + 'Event', event_data);
                        $('input[name="' + opts.name + '_task"]', form).val('post_unlock');

                        layout();
                    },
                    complete: function()
                    {
                        $('button:submit', form).button('enable');

                        // resets
                        $('input[name="' + opts.name + '_password"]', form).val('');
                        $('input[name="' + opts.name + '_headertext"]', form).val('');
                        $('input[type="' + opts.name + '_password"]', form).val('');
                    }
                });
            };

			// loading image
			function loading()
            {
				var form = $('form', $this);
				
				if ($('.' + opts.name + '_loading', form).length > 0)
                {
					$('.' + opts.name +  '_loading', form).remove();
				}
                else
                {
					$('input[name="' + opts.name + '_articlepassword"]', form).after('<div class="' + opts.name + '_loading" title="..."></div>');
				}
				return false;
			};

            /**
             * Layout area
             * @return	void
             */
            function layout()
            {
                // UI Buttons
                $('button', $this).filter(
                    function()
                    {
                        return $(this).data( opts.name + 'UiButton' ) && typeof $(this).data('uiButton') === 'undefined';
                    }
                ).each(
                    function()
                    {
                        var	element = $(this),
                            data = element.data( opts.name + 'UiButton' );
                        element.button( (typeof data === 'object' ? data : null) );
                    }
                );
            };

            /**
             * Register Events
             */
            function initEvents()
            {
                var on_events = new Array();
                on_events[0] = {
                    element : 'form',
                    event : 'submit.' + opts.name
                };

                $.each(on_events, function() {

                    $this.on(this.event, this.element,
                        function(e)
                        {
                            var element = $(this),
                                event = element.data(opts.name + 'Event');


                            // no event
                            if (typeof event === 'undefined') return true;

                            if (typeof event !== 'array') {
                                // make array
                                element.data(opts.name + 'Event', $.makeArray(event));
                            }

                            $.each(element.data(opts.name + 'Event'),
                                function()
                                {
                                    if (eval('typeof ' + this.type + '_' + this.name) === 'function') {

                                        eval(this.type + '_' + this.name + '(element, e);');
                                        return false;
                                    }
                                }
                            );
                        }
                    );
                });
            };
			
		};
	})(jQuery);
}