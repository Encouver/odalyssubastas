function LeftMenu() {
    this.shown = true;

    this.hide = function(anim) {
        if (anim) {
            if (wait_anim) return;
            wait_anim =true;
            $("div.left_menu").fadeTo("slow", 0.4);
            $("div.left_menu").animate({
                left: "-160px"	
            }, {
                duration: 100,
                complete: function () {
                    wait_anim =false;
                }
            });	
        }
        else {
            $("div.left_menu").css('opacity', 0.4);
            $("div.left_menu").css('left', '-160px');
        }
    }

    this.show = function(anim) {
        if (anim) {
            if (wait_anim) return;
            wait_anim =true;
            $("div.left_menu").animate({
                left: "0px"
            }, {
                duration: 100,
                complete: function () {
                    wait_anim =false;
                }
            });
            $("div.left_menu").fadeTo("slow", 1);
        }
        else {
            $("div.left_menu").css('opacity', 1);
            $("div.left_menu").css('left', '0px');
        }
    }

    this.toggle = function() {	
        if (this.shown)
            this.hide(true);
        else
            this.show(true);
        this.shown = !this.shown;
    }

    this.setStartStatus = function(start_val) {
        this.setToggleLink(start_val);
        if (start_val) {
            this.show(false);
        } else {
            this.hide(false);
        }	
        this.shown =start_val;
    }

    this.setToggleLink = function(status) {
        $("#toggle_left_menu").text(!status ? '<<' : '>>');
        $("#toggle_left_menu").attr('title', !status ? 'Hide' : 'Pin');
    }

    this.selectItem = function(item) {
        $(item).closest('ul').children('li.selected').removeClass('selected');
        $(item).parent('li').toggleClass('selected');
    }

    this.init = function(obj) {
        jQuery("#toggle_left_menu").click(function(event) {
            event.preventDefault();
            obj.setToggleLink(!obj.shown);
            obj.toggle();
        });
        jQuery("div.left_menu").mouseenter(function(event) {
            if (obj.shown) return;
            obj.show(true);
        });
        jQuery("div.left_menu").mouseleave(function(event) {
            if (obj.shown) return;
            obj.hide(true);
        });
        jQuery("div.left_menu ul li a").click(function(event) {
            obj.selectItem(this);
        });
    }
}

var wait_anim =false;
var left_menu = new LeftMenu();

jQuery(document).ready(function() {
    left_menu.init(left_menu);
});