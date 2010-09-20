
ModalChoiceWidget = {
  _selectors:{
    button_new:'#widget-button-new',
    button_edit:'#widget-button-edit',
    button_use:'#widget-button-use',
    button_delete:'#widget-button-delete',
    canvas:'#widget-canvas',
    autocomplete:'#widget-autocomplete',
    form:'#widget-form',
    hidden:'#widget-hidden',
    button_modal_add:'.sf_admin_action_new.modal',
    button_modal_cancel:'.sf_admin_action_cancel.modal'
  },
  clickNew:function()
  {
    ModalChoiceWidget.loadForm();
  },
  clickUse:function()
  {
    ModalChoiceWidget.toggle(false);
    jQuery(ModalChoiceWidget._selectors.autocomplete).focus();
  },
  clickEdit:function()
  {
    if(jQuery(ModalChoiceWidget._selectors.hidden).val())
    {
      clone = 'new' == jQuery(ModalChoiceWidget._selectors.hidden).attr('rel') ? 0 : 1;
      ModalChoiceWidget.loadForm(jQuery(ModalChoiceWidget._selectors.hidden).val(), clone);
    }
  },
  clickDelete:function()
  {
    if('new' == jQuery(ModalChoiceWidget._selectors.hidden).attr('rel'))
    {
      url = ModalChoiceWidgetConfig.deleteUrl.replace(/\/0/, '/'+jQuery(ModalChoiceWidget._selectors.hidden).val());
      jQuery.post(url, {sf_method: 'delete'});
      jQuery(ModalChoiceWidget._selectors.hidden).val('');
      jQuery(ModalChoiceWidget._selectors.hidden).attr('rel', 'old');
      jQuery(ModalChoiceWidget._selectors.canvas).html(ModalChoiceWidget.empty);
    }
  },
  clickModalAdd:function()
  {
    form = jQuery(ModalChoiceWidget._selectors.form);
    jQuery.post(form.attr('action'), form.serialize(), ModalChoiceWidget.updateForm);
  },
  updateForm:function(data)
  {
    preview = [];
    selector = '#'+ModalChoiceWidgetConfig.model.toLowerCase()+'_';
    jQuery.each(ModalChoiceWidgetConfig.fields, function(i,v){preview.push(jQuery(selector+v).val());});
    jQuery(ModalChoiceWidget._selectors.hidden).val(jQuery('input'+selector+'id', data).val());
    jQuery(ModalChoiceWidget._selectors.hidden).attr('rel', 'new');
    jQuery(ModalChoiceWidget._selectors.canvas).html(preview.join(', '));
    jQuery.unblockUI();
  },
  loadForm:function(id,clone)
  {
    currentUrl = ModalChoiceWidgetConfig.formUrl+(id?'/'+id:'')+(id&&clone?'/1':'')
    jQuery.get(currentUrl, ModalChoiceWidget.showForm);
  },
  showForm:function(data)
  {
    jQuery.blockUI({ message: data });
    jQuery(ModalChoiceWidget._selectors.button_modal_add).click(ModalChoiceWidget.clickModalAdd);
    jQuery(ModalChoiceWidget._selectors.button_modal_cancel).click(jQuery.unblockUI);
  },
  autocompleteResult:function(event,item){
    ModalChoiceWidget.clickDelete();
    jQuery(ModalChoiceWidget._selectors.hidden).val(item[0]);
    jQuery(ModalChoiceWidget._selectors.hidden).attr('rel', 'old');
    jQuery(ModalChoiceWidget._selectors.canvas).html(ModalChoiceWidget.toString(item));
    ModalChoiceWidget.toggle(true);
  },
  autocompleteOptions:{
    formatItem:function(item,pos,count,q){return ModalChoiceWidget.toString(item,pos,count,q)},
    formatResult:function(item,pos,count,q){return ModalChoiceWidget.toString(item,pos,count,q)},
  },
  toString:function(item,pos,count,q)
  {
    return item.slice(1).join(', ');
  },
  toggle:function(canvas)
  {
    if(canvas)
    {
      jQuery(ModalChoiceWidget._selectors.autocomplete).hide();
      jQuery(ModalChoiceWidget._selectors.canvas).show();
    }
    else
    {
      jQuery(ModalChoiceWidget._selectors.autocomplete).show();
      jQuery(ModalChoiceWidget._selectors.canvas).hide();
    }
  },
  init:function()
  {
    ModalChoiceWidget.empty = jQuery(ModalChoiceWidget._selectors.canvas).html();
    jQuery(ModalChoiceWidget._selectors.button_new).click(ModalChoiceWidget.clickNew);
    jQuery(ModalChoiceWidget._selectors.button_use).click(ModalChoiceWidget.clickUse);
    jQuery(ModalChoiceWidget._selectors.button_edit).click(ModalChoiceWidget.clickEdit);
    jQuery(ModalChoiceWidget._selectors.button_delete).click(ModalChoiceWidget.clickDelete);
    jQuery(ModalChoiceWidget._selectors.autocomplete).autocomplete(ModalChoiceWidgetConfig.autocompleteUrl, ModalChoiceWidget.autocompleteOptions).result(ModalChoiceWidget.autocompleteResult);
    if(jQuery(ModalChoiceWidget._selectors.hidden).val())
    {
      jQuery.get(ModalChoiceWidgetConfig.autocompleteUrl+'/'+jQuery(ModalChoiceWidget._selectors.hidden).val(), {}, function(data){jQuery(ModalChoiceWidget._selectors.canvas).html(ModalChoiceWidget.toString(data.split('|')));});
    }
    jQuery(ModalChoiceWidget._selectors.autocomplete).blur(function(){ModalChoiceWidget.toggle(true);});
  }
}

jQuery(document).ready(ModalChoiceWidget.init);
