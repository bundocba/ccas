/*
Script: TextboxList.js
	Displays a textbox as a combination of boxes an inputs (eg: facebook tokenizer)

	Authors:
		Guillermo Rauch
		
	Note:
		TextboxList is not priceless for commercial use. See <http://devthought.com/projects/mootools/textboxlist/>. 
		Purchase to remove this message.
*/

var TextboxList = new Class({
    Implements: [Options, Events],
    plugins: [],
    options: {
        prefix: "textboxlist",
        max: null,
        unique: false,
        uniqueInsensitive: true,
        endEditableBit: true,
        startEditableBit: true,
        hideEditableBits: true,
        inBetweenEditableBits: true,
        keys: {
            previous: Event.Keys.left,
            next: Event.Keys.right
        },
        bitsOptions: {
            editable: {},
            box: {}
        },
        plugins: {},
        check: function (a) {
            return a.clean().replace(/,/g, "") != ""
        },
        encode: function (a) {
            return a.map(function (a) {
                a = $chk(a[0]) ? a[0] : a[1];
                return $chk(a) ? a : null
            }).clean().join(",")
        },
        decode: function (a) {
            return a.split(",")
        }
    },
    initialize: function (a, b) {
        this.setOptions(b);
        this.original = $(a).setStyle("display", "none").set("autocomplete", "off").addEvent("focus", this.focusLast.bind(this));
        this.container = (new Element("div", {
            "class": this.options.prefix
        })).inject(a, "after");		
        this.container.addEvent("click", function (a) {
            if ((a.target == this.list || a.target == this.container) && (!this.focused || $(this.current) != this.list.getLast())) this.focusLast()
        }.bind(this));
        this.list = (new Element("ul", {
            "class": this.options.prefix + "-bits"
        })).inject(this.container);
        for (var c in this.options.plugins) this.enablePlugin(c, this.options.plugins[c]);
        ["check", "encode", "decode"].each(function (a) {
            this.options[a] = this.options[a].bind(this)
        }, this);
        this.afterInit()
    },
    enablePlugin: function (a, b) {
        this.plugins[a] = new(TextboxList[a.camelCase().capitalize()])(this, b)
    },
    afterInit: function () {
        if (this.options.unique) this.index = [];
        if (this.options.endEditableBit) this.create("editable", null, {
            tabIndex: this.original.tabIndex
        }).inject(this.list);
        var a = this.update.bind(this);
        this.addEvent("bitAdd", a, true).addEvent("bitRemove", a, true);
        document.addEvents({
            click: function (a) {
                if (!this.focused) return;
                if (a.target.className.contains(this.options.prefix)) {
                    if (a.target == this.container) return;
                    var b = a.target.getParent("." + this.options.prefix);
                    if (b == this.container) return
                }
                this.blur()
            }.bind(this),
            keydown: function (a) {
                if (!this.focused || !this.current) return;
                var b = this.current.is("editable") ? this.current.getCaret() : null;
                var c = this.current.getValue()[1];
                var d = ["shift", "alt", "meta", "ctrl"].some(function (b) {
                    return a[b]
                });
                var e = d || this.current.is("editable") && this.current.isSelected();
                switch (a.code) {
                case Event.Keys.backspace:
                    if (this.current.is("box")) {
                        a.stop();
                        return this.current.remove()
                    };
                case this.options.keys.previous:
                    if (this.current.is("box") || (b == 0 || !c.length) && !e) {
                        a.stop();
                        this.focusRelative("previous")
                    }
                    break;
                case Event.Keys["delete"]:
                    if (this.current.is("box")) {
                        a.stop();
                        return this.current.remove()
                    };
                case this.options.keys.next:
                    if (this.current.is("box") || b == c.length && !e) {
                        a.stop();
                        this.focusRelative("next")
                    }
                }
            }.bind(this)
        });
        this.setValues(this.options.decode(this.original.get("value")))
    },
    create: function (a, b, c) {
        if (a == "box") {
            if (!b[0] && !b[1] || $chk(b[1]) && !this.options.check(b[1])) return false;
            if ($chk(this.options.max) && this.list.getChildren("." + this.options.prefix + "-bit-box").length + 1 > this.options.max) return false;
            if (this.options.unique && this.index.contains(this.uniqueValue(b))) return false
        }
        return new(TextboxListBit[a.capitalize()])(b, this, $merge(this.options.bitsOptions[a], c))
    },
    uniqueValue: function (a) {
        return $chk(a[0]) ? a[0] : this.options.uniqueInsensitive ? a[1].toLowerCase() : a[1]
    },
    onFocus: function (a) {
        if (this.current) this.current.blur();
        $clear(this.blurtimer);
        this.current = a;
        this.container.addClass(this.options.prefix + "-focus");
        if (!this.focused) {
            this.focused = true;
            this.fireEvent("focus", a)
        }
    },
    onBlur: function (a, b) {
        this.current = null;
        this.container.removeClass(this.options.prefix + "-focus");
        this.blurtimer = this.blur.delay(b ? 0 : 200, this)
    },
    onAdd: function (a) {
        if (this.options.unique && a.is("box")) this.index.push(this.uniqueValue(a.value));
        if (a.is("box")) {
            var b = this.getBit($(a).getPrevious());
            if (b && b.is("box") && this.options.inBetweenEditableBits || !b && this.options.startEditableBit) {
                var c = this.create("editable").inject(b || this.list, b ? "after" : "top");
                if (this.options.hideEditableBits) c.hide()
            }
        }
    },
    onRemove: function (a) {
        if (!this.focused) return;
        if (this.options.unique && a.is("box")) this.index.erase(this.uniqueValue(a.value));
        var b = this.getBit($(a).getPrevious());
        if (b && b.is("editable")) b.remove();
        this.focusRelative("next", a)
    },
    focusRelative: function (a, b) {
        var c = this.getBit($($pick(b, this.current))["get" + a.capitalize()]());
        if (c) c.focus();
        return this
    },
    focusLast: function () {
        var a = this.list.getLast();
        if (a) this.getBit(a).focus();
        return this
    },
    blur: function () {
        if (!this.focused) return this;
        if (this.current) this.current.blur();
        this.focused = false;
        return this.fireEvent("blur")
    },
    add: function (a, b, c, d) {
        var e = this.create("box", [b, a, c]);
        if (e) {
            if (!d) d = this.list.getLast("." + this.options.prefix + "-bit-box");
            e.inject(d || this.list, d ? "after" : "top")
        }
        return this
    },
    getBit: function (a) {
        return $type(a) == "element" ? a.retrieve("textboxlist:bit") : a
    },
    getValues: function () {
        return this.list.getChildren().map(function (a) {
            var b = this.getBit(a);
            if (b.is("editable")) return null;
            return b.getValue()
        }, this).clean()
    },
    setValues: function (a) {
        if (!a) return;
        a.each(function (a) {
            if (a) this.add.apply(this, $type(a) == "array" ? [a[1], a[0], a[2]] : [a])
        }, this)
    },
    update: function () {
        this.original.set("value", this.options.encode(this.getValues()))
    }
});
var TextboxListBit = new Class({
    Implements: Options,
    initialize: function (a, b, c) {
        this.name = this.type.capitalize();
        this.value = a;
        this.textboxlist = b;
        this.setOptions(c);
        this.prefix = this.textboxlist.options.prefix + "-bit";
        this.typeprefix = this.prefix + "-" + this.type;
        this.bit = (new Element("li")).addClass(this.prefix).addClass(this.typeprefix).store("textboxlist:bit", this);
        this.bit.addEvents({
            mouseenter: function () {
                this.bit.addClass(this.prefix + "-hover").addClass(this.typeprefix + "-hover")
            }.bind(this),
            mouseleave: function () {
                this.bit.removeClass(this.prefix + "-hover").removeClass(this.typeprefix + "-hover")
            }.bind(this)
        })
    },
    inject: function (a, b) {
        this.bit.inject(a, b);
        this.textboxlist.onAdd(this);
        return this.fireBitEvent("add")
    },
    focus: function () {
        if (this.focused) return this;
        this.show();
        this.focused = true;
        this.textboxlist.onFocus(this);
        this.bit.addClass(this.prefix + "-focus").addClass(this.prefix + "-" + this.type + "-focus");
        return this.fireBitEvent("focus")
    },
    blur: function () {
        if (!this.focused) return this;
        this.focused = false;
        this.textboxlist.onBlur(this);
        this.bit.removeClass(this.prefix + "-focus").removeClass(this.prefix + "-" + this.type + "-focus");
        return this.fireBitEvent("blur")
    },
    remove: function () {
        this.blur();
        this.textboxlist.onRemove(this);
        this.bit.destroy();
        return this.fireBitEvent("remove")
    },
    show: function () {
        this.bit.setStyle("display", "block");
        return this
    },
    hide: function () {
        this.bit.setStyle("display", "none");
        return this
    },
    fireBitEvent: function (a) {
        a = a.capitalize();
        this.textboxlist.fireEvent("bit" + a, this).fireEvent("bit" + this.name + a, this);
        return this
    },
    is: function (a) {
        return this.type == a
    },
    setValue: function (a) {
        this.value = a;
        return this
    },
    getValue: function () {
        return this.value
    },
    toElement: function () {
        return this.bit
    }
});
TextboxListBit.Editable = new Class({
    Extends: TextboxListBit,
    options: {
        tabIndex: null,
        growing: true,
        growingOptions: {},
        stopEnter: true,
        addOnBlur: false,
        addKeys: Event.Keys.enter
    },
    type: "editable",
    initialize: function (a, b, c) {
        this.parent(a, b, c);
        this.element = (new Element("input", {
            type: "text",
            "class": this.typeprefix + "-input",
            autocomplete: "off",
            value: this.value ? this.value[1] : ""
        })).inject(this.bit);
        if ($chk(this.options.tabIndex)) this.element.tabIndex = this.options.tabIndex;
        if (this.options.growing) new GrowingInput(this.element, this.options.growingOptions);
        this.element.addEvents({
            focus: function () {
                this.focus(true)
            }.bind(this),
            blur: function () {
                this.blur(true);
                if (this.options.addOnBlur) this.toBox()
            }.bind(this)
        });
        if (this.options.addKeys || this.options.stopEnter) {
            this.element.addEvent("keydown", function (a) {
                if (!this.focused) return;
                if (this.options.stopEnter && a.code === Event.Keys.enter) a.stop();
                if ($splat(this.options.addKeys).contains(a.code)) {
                    a.stop();
                    this.toBox()
                }
            }.bind(this))
        }
    },
    hide: function () {
        this.parent();
        this.hidden = true;
        return this
    },
    focus: function (a) {
        this.parent();
        if (!a) this.element.focus();
        return this
    },
    blur: function (a) {
        this.parent();
        if (!a) this.element.blur();
        if (this.hidden && !this.element.value.length) this.hide();
        return this
    },
    getCaret: function () {
        if (this.element.createTextRange) {
            var a = document.selection.createRange().duplicate();
            a.moveEnd("character", this.element.value.length);
            if (a.text === "") return this.element.value.length;
            return this.element.value.lastIndexOf(a.text)
        } else return this.element.selectionStart
    },
    getCaretEnd: function () {
        if (this.element.createTextRange) {
            var a = document.selection.createRange().duplicate();
            a.moveStart("character", -this.element.value.length);
            return a.text.length
        } else return this.element.selectionEnd
    },
    isSelected: function () {
        return this.focused && this.getCaret() !== this.getCaretEnd()
    },
    setValue: function (a) {
        this.element.value = $chk(a[0]) ? a[0] : a[1];
        if (this.options.growing) this.element.retrieve("growing").resize();
        return this
    },
    getValue: function () {
        return [null, this.element.value, null]
    },
    toBox: function () {
        var a = this.getValue();
        var b = this.textboxlist.create("box", a);
        if (b) {
            b.inject(this.bit, "before");
            this.setValue([null, "", null]);
            return b
        }
        return null
    }
});
TextboxListBit.Box = new Class({
    Extends: TextboxListBit,
    options: {
        deleteButton: true
    },
    type: "box",
    initialize: function (a, b, c) {
        this.parent(a, b, c);
        this.bit.set("html", $chk(this.value[2]) ? this.value[2] : this.value[1]);
        this.bit.addEvent("click", this.focus.bind(this));
        if (this.options.deleteButton) {
            this.bit.addClass(this.typeprefix + "-deletable");
            this.close = (new Element("a", {
                href: "javascript:void(0)",
                "class": this.typeprefix + "-deletebutton",
                events: {
                    click: this.remove.bind(this)
                }
            })).inject(this.bit)
        }
        this.bit.getChildren().addEvent("click", function (a) {
            a.stop()
        })
    }
})