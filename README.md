# sfAdminHijackPlugin

The `sfJqueryWidgetsPlugin` is a symfony plugin that adds a simple jquery widget for managing doctrine one-to-many relations to the list of available widgets bundled in the framework.

It bears little functionality itself but instead *hijacks* a generated admin module which is used in a jQuery modal dialog.

## Installation

  * Install the Plugin
  
        $ symfony plugin:install sfAdminHijackPlugin
      
  * Clear Your Cache
  
        $ symfony cc
  
  * Publish Plugins Assets
  
        $ symfony plugin:publish-assets

## Usage

Simply add this widget in your form class for the one-to-many relation field.

      $this->widgetSchema['category_id'] = new sfWidgetFormJQueryAdminHijackDoctrineChoice(array(
        'model' => 'Category',
        'module' => 'category',
        'fields' => array('name')
      ));

Where 'module' is the name of the module you generated using the ``doctrine:generate-admin`` task.

## Known limitations

  * Only one-to-many relations are supported.
  * The widget assumes that the primary key of the related model is called ``id``.
  * JQuery has to be present
  * The related instances created with the new dialog are saved on the fly. If used uncaringly this can produce a lot of orphaned entries in your database.
