﻿// Plugin created by Daenu Probst http://codebrewery.blogspot.com/

// Global variables
var formidable_tabIndex = 1;
var formidable_zIndex = 9999;

// Define a focus selector
jQuery.extend(jQuery.expr[':'], {  focus: "a == document.activeElement"});

(function ($) {
    $.fn.formidable = function (options) {
        var opts = $.extend({}, $.fn.formidable.defaults, options);
		
        return this.each(function () {
			var original = $(this);
			
			// labels
			original.find('label').each(function() {
				var label = $(this);
				var _for = label.attr('for');
				
				if(_for == '') return;
				
				label.attr('for', '');
				var target = original.find('#' + _for);
				label.click(function() {
					if (target.is(':radio')) {
						target.click();
						target.change();
					}
					else if (target.is(':checkbox')) {
						if(target.is(':checked'))
							target.attr('checked', '');
						else
							target.attr('checked', 'checked');
							
						target.change();
					}
					else {
						target.focus();
					}
				});
				
				if (opts.noCss) {
					label.css('font', opts.form.font);
				}
			});
			
			if (opts.noCss) {
				original.css('margin', '5px');
			}
			
			// fieldsets
			original.find('fieldset').each(function() {
				var fieldset = $(this);
				fieldset.addClass('formidable-fieldset');
				if (opts.noCss) {
					fieldset.css('padding', opts.form.fieldsetPadding);
				}
			});
			
			// textboxes
			original.find(':text').each(function() {
				var input = $(this);
				input.addClass(opts.classes.text);
				
				input.hover(function() {
					if (opts.noCss) {				
						if ($.browser.msie)
							input.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textbox.backgroundHoverTop + '\', endColorstr=\'' + opts.textbox.backgroundHoverBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							input.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textbox.backgroundHoverTop + '), color-stop(100%,' + opts.textbox.backgroundHoverBottom + '))');
						else if ($.browser.mozilla)
							input.css('background', '-moz-linear-gradient(top, ' + opts.textbox.backgroundHoverTop + ' 0%, ' + opts.textbox.backgroundHoverBottom + ' 100%)');
						else
							input.css('background', opts.textbox.backgroundHoverTop);
						
						input.css('border', '1px solid ' + opts.textbox.borderHover);
					}
				}, function() {
					if (opts.noCss) {
						if (input.data('hasFocus')) return;
						if ($.browser.msie)
							input.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textbox.backgroundTop + '\', endColorstr=\'' + opts.textbox.backgroundBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							input.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textbox.backgroundTop + '), color-stop(100%,' + opts.textbox.backgroundBottom + '))');
						else if ($.browser.mozilla)
							input.css('background', '-moz-linear-gradient(top, ' + opts.textbox.backgroundTop + ' 0%, ' + opts.textbox.backgroundBottom + ' 100%)');
						else
							input.css('background', opts.textbox.backgroundTop);
						
						input.css('border', '1px solid ' + opts.textbox.border);
					}
				});
				
				input.focus(function() {
					input.data('hasFocus', true);
					if (opts.noCss) {		
						if ($.browser.msie)
							input.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textbox.backgroundHoverTop + '\', endColorstr=\'' + opts.textbox.backgroundHoverBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							input.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textbox.backgroundHoverTop + '), color-stop(100%,' + opts.textbox.backgroundHoverBottom + '))');
						else if ($.browser.mozilla)
							input.css('background', '-moz-linear-gradient(top, ' + opts.textbox.backgroundHoverTop + ' 0%, ' + opts.textbox.backgroundHoverBottom + ' 100%)');
						else
							input.css('background', opts.textbox.backgroundHoverTop);
						
						input.css('border', '1px solid ' + opts.textbox.borderHover);
					}
				});
				input.blur(function() {
					input.data('hasFocus', false);
					if (opts.noCss) {
						if ($.browser.msie)
							input.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textbox.backgroundTop + '\', endColorstr=\'' + opts.textbox.backgroundBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							input.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textbox.backgroundTop + '), color-stop(100%,' + opts.textbox.backgroundBottom + '))');
						else if ($.browser.mozilla)
							input.css('background', '-moz-linear-gradient(top, ' + opts.textbox.backgroundTop + ' 0%, ' + opts.textbox.backgroundBottom + ' 100%)');
						else
							input.css('background', opts.textbox.backgroundTop);
						
						input.css('border', '1px solid ' + opts.textbox.border);
					}
				});

				
				if (opts.noCss) {
					if ($.browser.msie)
						input.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textbox.backgroundTop + '\', endColorstr=\'' + opts.textbox.backgroundBottom + '\',GradientType=0)');
					else if ($.browser.webkit)
						input.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textbox.backgroundTop + '), color-stop(100%,' + opts.textbox.backgroundBottom + '))');
					else if ($.browser.mozilla)
						input.css('background', '-moz-linear-gradient(top, ' + opts.textbox.backgroundTop + ' 0%, ' + opts.textbox.backgroundBottom + ' 100%)');
					else
						input.css('background', opts.textbox.backgroundTop);
						
					input.css('display', 'block')
						 .css('margin-bottom', '5px')
						 .css('border', '1px solid ' + opts.textbox.border)
						 .css('padding', opts.form.padding + 'px')
						 .css('border-radius', opts.textbox.borderRadius)
						 .css('-moz-border-radius', opts.textbox.borderRadius)
						 .css('-webkit-border-radius', opts.textbox.borderRadius)
						 .css('-webkit-box-shadow', 'inset 0px 0px ' + opts.textbox.innerShadowBlur + 'px ' + opts.textbox.innerShadow)
						 .css('-moz-box-shadow', 'inset 0px 0px ' + opts.textbox.innerShadowBlur + 'px ' + opts.textbox.innerShadow)
						 .css('box-shadow', 'inset 0px 0px ' + opts.textbox.innerShadowBlur + 'px ' + opts.textbox.innerShadow)
						 .css('font', opts.form.font)
						 .width(opts.form.width);
				}
			});
			
			// checkboxes
			original.find(':checkbox').each(function() {
				var input = $(this);
				input.css('display', 'none');
				input.addClass(opts.classes.checkbox);
				
				var div = $('<div class="' + opts.classes.checkbox + '" />');
				var divInner = $('<div class="formidable-checkbox-replacement-inner" />');
				var toggler = $('<div class="formidable-checkbox-toggler" />');
				
				divInner.append(toggler);
				div.append(divInner);
				
				if (opts.noCss) {				
					div.css('display', 'inline-block')
					   .css('border', '1px solid ' + opts.checkbox.border)
					   .css('background-color', opts.checkbox.background)
					   .css('margin-bottom', '-2px')
					   .css('border-radius', opts.checkbox.borderRadius)
					   .css('-moz-border-radius', opts.checkbox.borderRadius)
					   .css('-webkit-border-radius', opts.checkbox.borderRadius)
					   .css('-webkit-box-shadow', 'inset 0px 0px ' + opts.checkbox.innerShadowBlur + 'px ' + opts.checkbox.innerShadow)
					   .css('-moz-box-shadow', 'inset 0px 0px ' + opts.checkbox.innerShadowBlur + 'px ' + opts.checkbox.innerShadow)
					   .css('box-shadow', 'inset 0px 0px ' + opts.checkbox.innerShadowBlur + 'px ' + opts.checkbox.innerShadow)
					   .width(opts.checkbox.size)
					   .height(opts.checkbox.size);
					   
					if	($.browser.msie && $.browser.version < 8) {
						div.css('display', 'inline')
						   .css('zoom', '1');
					}
					   
					divInner.css('border', '1px solid ' + opts.checkbox.innerBorder)
							.height(opts.checkbox.size - 2)
							.width(opts.checkbox.size - 2);
					   
					toggler.css('margin', '1px')
						   .css('background-color', 'transparent')
						   .width(opts.checkbox.size - 4)
						   .height(opts.checkbox.size - 4);
				}
				
				input.after(div);
				
				if (!input.is(':checked')) {
					toggler.css('background-color', 'transparent');
				}
				else {
					toggler.css('background-color', opts.toggle.background);
				}
				
				div.click(function() {
					if (input.is(':checked')) {
						toggler.css('background-color', 'transparent');
						input.attr('checked', '');
					}
					else {
						toggler.css('background-color', opts.toggle.background);
						input.attr('checked', 'checked');
					}
				});
				
				div.keydown(function(e) {
					if (e.keyCode == 32) {
						if(input.is(':checked'))
							input.attr('checked', '');
						else
							input.attr('checked', 'checked');
							
						input.change();					
					}
				});
				
				div.hover(function() {
					if (opts.noCss) {
						div.css('background-color', opts.checkbox.backgroundHover)
						   .css('border', '1px solid ' + opts.checkbox.borderHover);
					}
				}, function() {
					if (opts.noCss) {
						div.css('background-color', opts.checkbox.background)
						   .css('border', '1px solid ' + opts.checkbox.border);
					}
				});
				
				input.change(function() {
					if (input.is(':checked')) {
						toggler.css('background-color', opts.toggle.background);					
					}
					else {
						toggler.css('background-color', 'transparent');
					}
				});
			});
			
			// radio
        	original.find(':radio').each(function() {
        		var input = $(this);
        		input.css('display', 'none');
				input.addClass(opts.classes.radio);
				
				var div = $('<div class="' + opts.classes.radio + '" />');
				var divInner = $('<div class="formidable-radio-replacement-inner" />');
				var toggler = $('<div class="formidable-radio-toggler" />');
				toggler.addClass('formidable-radio-name-' + input.attr('name'));
				
				divInner.append(toggler);
				div.append(divInner);
				
				if (opts.noCss) {
					div.css('display', 'inline-block')
					   .css('border', '1px solid ' + opts.radio.border)
					   .css('background-color', opts.radio.background)
					   .css('margin-bottom', '-2px')
					   .css('border-radius', '10px')
					   .css('-moz-border-radius', '10px')
					   .css('-webkit-border-radius', '10px')
					   .css('-webkit-box-shadow', 'inset 0px 0px ' + opts.radio.innerShadowBlur + 'px ' + opts.radio.innerShadow)
					   .css('-moz-box-shadow', 'inset 0px 0px ' + opts.radio.innerShadowBlur + 'px ' + opts.radio.innerShadow)
					   .css('box-shadow', 'inset 0px 0px ' + opts.radio.innerShadowBlur + 'px ' + opts.radio.innerShadow)
					   .width(opts.radio.size)
					   .height(opts.radio.size);
					   
					if	($.browser.msie && $.browser.version < 8) {
						div.css('display', 'inline')
						   .css('zoom', '1');
					}
					   
					divInner.css('border', '1px solid ' + opts.radio.innerBorder)
							.css('border-radius', '10px')
						    .css('-moz-border-radius', '10px')
						    .css('-webkit-border-radius', '10px')
							.width(opts.radio.size - 2)
							.height(opts.radio.size - 2);
					   
					toggler.css('background-color', 'transparent')
						   .css('border-radius', '10px')
						   .css('margin', '1px')
					   	   .css('-moz-border-radius', '10px')
					   	   .css('-webkit-border-radius', '10px')
						   .width(opts.radio.size - 4)
						   .height(opts.radio.size - 4);
						   
				}
				
				input.after(div);
				
				if (!input.is(':checked')) {
					toggler.css('background-color', 'transparent');
				}
				else {
					toggler.css('background-color', opts.toggle.background);
				}
				
				input.change(function() {
					if (input.is(':checked')) {
						$('.formidable-radio-name-' + input.attr('name')).css('background-color', 'transparent');
						toggler.css('background-color', opts.toggle.background);					
					}
					else {
						toggler.css('background-color', 'transparent');
					}
				});
				
				div.click(function() {
					if (!input.is(':checked')) {
						$('[name="' + input.attr('name') + '"]').attr('checked', '');
						$('.formidable-radio-name-' + input.attr('name')).css('background-color', 'transparent');
						toggler.css('background-color', opts.toggle.background);
						input.attr('checked', 'checked');
					}
				});
				
				div.keydown(function(e) {
					if (e.keyCode == 32) {
						if(input.is(':checked'))
							input.attr('checked', '');
						else
							input.attr('checked', 'checked');
							
						input.change();					
					}
				});
				
				div.hover(function() {
					if (opts.noCss) {
						div.css('background-color', opts.radio.backgroundHover)
						   .css('border', '1px solid ' + opts.radio.borderHover);
					}
				}, function() {
					if (opts.noCss) {
						div.css('background-color', opts.radio.background)
						   .css('border', '1px solid ' + opts.radio.border);
					}
				});
			});
			
			// select
			original.find('select').each(function() {
				var select = $(this);
				
				var div = $('<div />');
				var arrowDown = $('<div class="formidable-select-arrowdown" />');
				var arrowUp = $('<div class="formidable-select-arrowup" />');

				
				var table = $('<table class="' + opts.classes.select + '" />');
				var tbody = $('<tbody />');
				var tr = $('<tr />');
				var trDropDown = $('<tr></tr>');
				var tdItems = $('<td />');
				var divItems = $('<div />');
				tdItems.attr('colspan', 2);
				var tdCurrent = $('<td />');
				var tdButton = $('<td />');
				
				select.children('option').each(function() {
					var item = $('<div />');
					item.text($(this).text());
					item.data('val', $(this).val());
					
					item.hover(function() {
						if (opts.noCss) {
							item.css('background-color', opts.select.optionHover);
						}
					}, function() {
						if (opts.noCss) {
							item.css('background-color', 'transparent');
						}
					});
					
					item.click(function() {
						select.val($(this).data('val'));
						select.change();					
					});
					
					if (opts.noCss) {
						item.css('padding', opts.select.optionPadding + 'px ' + opts.form.padding + 'px ' + opts.select.optionPadding + 'px ' + opts.form.padding + 'px')
							.css('cursor', 'default')
							.width(opts.form.width);
					}
					divItems.append(item);
				});
				
				tdButton.append(arrowDown);
				tdButton.append(arrowUp);
				
				tdCurrent.text(select.val());
				
				tr.append(tdCurrent)
				  .append(tdButton);
				
				tdItems.append(divItems);
			   	trDropDown.append(tdItems);
				
				tbody.append(tr)
					 .append(trDropDown);
				table.append(tbody);
				
				div.append(table);
				
				select.after(div);
				
				table.click(function() {
					trDropDown.toggle();
					arrowUp.toggle();
					arrowDown.toggle();
				});
				
				table.keydown(function(e) {
					if (e.keyCode == 40) {
						if (select.children('option').length - 1 > select[0].selectedIndex) {
							select[0].selectedIndex = select[0].selectedIndex + 1;
							select.change();
						}
						e.preventDefault();
					}
					else if(e.keyCode == 38) {
						if (select[0].selectedIndex > 0) {
							select[0].selectedIndex = select[0].selectedIndex - 1;
							select.change();
						}
						
						e.preventDefault();
					} 
					else {
						var character = String.fromCharCode(e.keyCode);
						select.children('option').each(function(i) {
							if ($(this).text().substr(0, 1).toLowerCase() == character.toLowerCase()) {
								select[0].selectedIndex = i;
								select.change();
							}
						});
					}
				});
				
				table.hover(function() {
					if (opts.noCss) {
						if ($.browser.msie)
							table.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.select.backgroundHoverTop + '\', endColorstr=\'' + opts.select.backgroundHoverBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							table.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.select.backgroundHoverTop + '), color-stop(100%,' + opts.select.backgroundHoverBottom + '))');
						else if ($.browser.mozilla)
							table.css('background', '-moz-linear-gradient(top, ' + opts.select.backgroundHoverTop + ' 0%, ' + opts.select.backgroundHoverBottom + ' 100%)');
						else
							table.css('background', opts.select.backgroundHoverTop);
							
						table.css('border', '1px solid ' + opts.select.borderHover);
					}
				}, function() {
					if(table.data('hasFocus')) return;
					if (opts.noCss) {
						if ($.browser.msie)
							table.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.select.backgroundTop + '\', endColorstr=\'' + opts.select.backgroundBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							table.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.select.backgroundTop + '), color-stop(100%,' + opts.select.backgroundBottom + '))');
						else if ($.browser.mozilla)
							table.css('background', '-moz-linear-gradient(top, ' + opts.select.backgroundTop + ' 0%, ' + opts.select.backgroundBottom + ' 100%)');
						else
							table.css('background', opts.select.backgroundTop);
							
						table.css('border', '1px solid ' + opts.select.border);
					}
				});
				
				table.focus(function() {
					table.data('hasFocus', true);
					
					if (opts.noCss) {
						if ($.browser.msie)
							table.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.select.backgroundHoverTop + '\', endColorstr=\'' + opts.select.backgroundHoverBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							table.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.select.backgroundHoverTop + '), color-stop(100%,' + opts.select.backgroundHoverBottom + '))');
						else if ($.browser.mozilla)
							table.css('background', '-moz-linear-gradient(top, ' + opts.select.backgroundHoverTop + ' 0%, ' + opts.select.backgroundHoverBottom + ' 100%)');
						else
							table.css('background', opts.select.backgroundHoverTop);
							
						table.css('border', '1px solid ' + opts.select.borderHover);
					}
				});
				table.blur(function() {
					trDropDown.hide();
					arrowUp.hide();
					arrowDown.show();
					
					table.data('hasFocus', false);
					
					if (opts.noCss) {
						if ($.browser.msie)
							table.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.select.backgroundTop + '\', endColorstr=\'' + opts.select.backgroundBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							table.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.select.backgroundTop + '), color-stop(100%,' + opts.select.backgroundBottom + '))');
						else if ($.browser.mozilla)
							table.css('background', '-moz-linear-gradient(top, ' + opts.select.backgroundTop + ' 0%, ' + opts.select.backgroundBottom + ' 100%)');
						else
							table.css('background', opts.select.backgroundTop);
							
						table.css('border', '1px solid ' + opts.select.border);
					}
				});
				
				if (opts.noCss) {
					select.css('display', 'none');
					
					if ($.browser.msie)
						table.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.select.backgroundTop + '\', endColorstr=\'' + opts.select.backgroundBottom + '\',GradientType=0)');
					else if ($.browser.webkit)
						table.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.select.backgroundTop + '), color-stop(100%,' + opts.select.backgroundBottom + '))');
					else if ($.browser.mozilla)
						table.css('background', '-moz-linear-gradient(top, ' + opts.select.backgroundTop + ' 0%, ' + opts.select.backgroundBottom + ' 100%)');
					else
						table.css('background', opts.select.backgroundTop);

					table.css('border', '1px solid ' + opts.select.border)
						 .css('position', 'absolute')
						 .css('border-spacing', '0')
						 .css('border-radius', opts.select.borderRadius)
						 .css('-moz-border-radius', opts.select.borderRadius)
						 .css('-webkit-border-radius', opts.select.borderRadius)
						 .css('font', opts.form.font)
						 .css('z-index', formidable_zIndex--)
						 .css('margin-bottom', '5px')
						 .width(opts.form.width + 2 * opts.form.padding + 2);
					tdButton.css('background-color', 'transparent')
							.css('vertical-align', 'middle')
						 	.css('text-align', 'center')
						  	.width(25);
					tdCurrent.css('padding', opts.form.padding + 'px')
							 .width(table.width() - tdButton.width());
					tdItems.css('padding', '0');
					arrowDown.css('border-top', '5px solid ' + opts.select.arrow)
						 	 .css('border-left', '4px solid transparent')
						 	 .css('border-right', '4px solid transparent')
						 	 .css('margin', '0 auto')
						 	 .width(1)
						 	 .height(1);
					arrowUp.css('border-bottom', '5px solid ' + opts.select.arrow)
						   .css('border-left', '4px solid transparent')
						   .css('border-right', '4px solid transparent')
						   .css('display', 'none')
						   .css('margin', '0 auto')
						   .width(1)
						   .height(1);
					trDropDown.css('display', 'none');
					divItems.css('overflow-y', 'scroll')
							.css('overflow-x', 'hidden')
							.css('max-height', opts.select.dropDownHeight + 'px')
							.height(opts.select.dropDownHeight)
							.width(table.width());
							  
					// placeholder for absolute positioned table
					div.width(table.width())
					   .height(table.height())
					   .css('margin-bottom', '7px');
					
					if (!$.browser.msie) {
						trDropDown.width(opts.form.width + 2 * opts.form.padding);
					}
					else {
						trDropDown.width(opts.form.width + opts.form.padding * 2 + 2);
					}
				}
				
				select.change(function() {
					tdCurrent.text(select.val());
				});
			});
			
			// textareas
			original.find('textarea').each(function() {
				var textarea = $(this);
				textarea.addClass(opts.classes.textarea);
				
				textarea.hover(function() {
					if (opts.noCss) {
						if ($.browser.msie)
							textarea.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textarea.backgroundHoverTop + '\', endColorstr=\'' + opts.textarea.backgroundHoverBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							textarea.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textarea.backgroundHoverTop + '), color-stop(100%,' + opts.textarea.backgroundHoverBottom + '))');
						else if ($.browser.mozilla)
							textarea.css('background', '-moz-linear-gradient(top, ' + opts.textarea.backgroundHoverTop + ' 0%, ' + opts.textarea.backgroundHoverBottom + ' 100%)');
						else
							textarea.css('background', opts.textarea.backgroundHoverTop);
							
						textarea.css('border', '1px solid ' + opts.textarea.borderHover);
					}
				}, function() {
					if(textarea.data('hasFocus')) return;
					
					if (opts.noCss) {
						if ($.browser.msie)
							textarea.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textarea.backgroundTop + '\', endColorstr=\'' + opts.textarea.backgroundBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							textarea.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textarea.backgroundTop + '), color-stop(100%,' + opts.textarea.backgroundBottom + '))');
						else if ($.browser.mozilla)
							textarea.css('background', '-moz-linear-gradient(top, ' + opts.textarea.backgroundTop + ' 0%, ' + opts.textarea.backgroundBottom + ' 100%)');
						else
							textarea.css('background', opts.textarea.backgroundTop);
							
						textarea.css('border', '1px solid ' + opts.textarea.border);
					}
				});
				
				textarea.focus(function() {
					textarea.data('hasFocus', true);
					
					if (opts.noCss) {
						if ($.browser.msie)
							textarea.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textarea.backgroundHoverTop + '\', endColorstr=\'' + opts.textarea.backgroundHoverBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							textarea.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textarea.backgroundHoverTop + '), color-stop(100%,' + opts.textarea.backgroundHoverBottom + '))');
						else if ($.browser.mozilla)
							textarea.css('background', '-moz-linear-gradient(top, ' + opts.textarea.backgroundHoverTop + ' 0%, ' + opts.textarea.backgroundHoverBottom + ' 100%)');
						else
							textarea.css('background', opts.textarea.backgroundHoverTop);
							
						textarea.css('border', '1px solid ' + opts.textarea.borderHover);
					}
				});
				
				textarea.blur(function() {
					textarea.data('hasFocus', false);
					
					if (opts.noCss) {
						if ($.browser.msie)
							textarea.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textarea.backgroundTop + '\', endColorstr=\'' + opts.textarea.backgroundBottom + '\',GradientType=0)');
						else if ($.browser.webkit)
							textarea.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textarea.backgroundTop + '), color-stop(100%,' + opts.textarea.backgroundBottom + '))');
						else if ($.browser.mozilla)
							textarea.css('background', '-moz-linear-gradient(top, ' + opts.textarea.backgroundTop + ' 0%, ' + opts.textarea.backgroundBottom + ' 100%)');
						else
							textarea.css('background', opts.textarea.backgroundTop);
							
						textarea.css('border', '1px solid ' + opts.textarea.border);
					}
				});

				
				if (opts.noCss) {
					if ($.browser.msie)
						textarea.css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + opts.textarea.backgroundTop + '\', endColorstr=\'' + opts.textarea.backgroundBottom + '\',GradientType=0)');
					else if ($.browser.webkit)
						textarea.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + opts.textarea.backgroundTop + '), color-stop(100%,' + opts.textarea.backgroundBottom + '))');
					else if ($.browser.mozilla)
						textarea.css('background', '-moz-linear-gradient(top, ' + opts.textarea.backgroundTop + ' 0%, ' + opts.textarea.backgroundBottom + ' 100%)');
					else
						textarea.css('background', opts.textarea.backgroundTop);
						
					textarea.css('display', 'block')
						 	.css('margin-bottom', '5px')
						 	.css('border', '1px solid ' + opts.textarea.border)
						 	.css('padding', opts.form.padding + 'px')
							.css('border-radius', opts.textarea.borderRadius)
							.css('-moz-border-radius', opts.textarea.borderRadius)
							.css('-webkit-border-radius', opts.textarea.borderRadius)
							.css('-webkit-box-shadow', 'inset 0px 0px ' + opts.textarea.innerShadowBlur + 'px ' + opts.textarea.innerShadow)
							.css('-moz-box-shadow', 'inset 0px 0px ' + opts.textarea.innerShadowBlur + 'px ' + opts.textarea.innerShadow)
							.css('box-shadow', 'inset 0px 0px ' + opts.textarea.innerShadowBlur + 'px ' + opts.textarea.innerShadow)
						 	.width(opts.form.width);
				}
			});
			
			original.find('div').css('cursor', 'default');
			
			// set formidable_tabIndex
			$('.' + opts.classes.text + 
			  ', .' + opts.classes.textarea + 
			  ', .' + opts.classes.checkbox + 
			  ', .' + opts.classes.radio + 
			  ', .' + opts.classes.select).each(function() {
				$(this).attr('tabindex', formidable_tabIndex++);
			});
        });
		
        return null;
    };
    $.fn.formidable.defaults = {
		classes: {
			text: 						'formidable-text',
			textarea: 					'formidable-textarea',
			checkbox: 					'formidable-checkbox',
			radio: 						'formidable-radio',
			select: 					'formidable-select'
		},
		form: {
			fieldsetPadding:			10,
			font:						'0.8em Segoe UI, Verdana, sans-serif',
			width: 						250,
			padding: 					5
		},
		textbox: {
			innerShadow:				'#def',
			innerShadowBlur:			6,
			border:						'#678',
			borderHover:   				'#234',
			borderRadius:				2,
			backgroundTop:				'#fafafa',
			backgroundBottom:			'#ffffff',
			backgroundHoverTop: 		'#f5f5f5',
			backgroundHoverBottom:		'#ffffff'
		},
		textarea: {
			innerShadow:				'#def',
			innerShadowBlur:			6,
			border:						'#777',
			borderHover:   				'#333',
			borderRadius:				2,
			backgroundTop:				'#fafafa',
			backgroundBottom:			'#ffffff',
			backgroundHoverTop: 		'#f5f5f5',
			backgroundHoverBottom:		'#ffffff'
		},
		checkbox: {
			size:						12,
			background:					'#fafafa',
			backgroundHover:			'#eeeeee',
			border:						'#555',
			borderHover:				'#222',
			innerBorder:				'#888',
			innerShadow:				'#aaa',
			innerShadowBlur:			6,
			borderRadius:				2
		},
		radio: {
			size:						12,
			background:					'#fafafa',
			backgroundHover:			'#eeeeee',
			border:						'#555',
			borderHover:				'#222',
			innerBorder:				'#888',
			innerShadow:				'#aaa',
			innerShadowBlur:			6,
			border:						'#555'
		},
		toggle: {
			background:					'#222',
			backgroundHover:			'#aaa'
		},
		select: {
			dropDownHeight:				150,
			optionHover:				'#eeeeee',
			optionPadding:				5,
			arrow:						'#333',
			border:						'#678',
			borderHover:   				'#234',
			borderRadius:				2,
			backgroundTop:				'#fefefe',
			backgroundBottom:			'#eeeeee',
			backgroundHoverTop: 		'#f6f6f6',
			backgroundHoverBottom:		'#fefefe'
		},
		noCss: 							true
    };
})(jQuery); 
