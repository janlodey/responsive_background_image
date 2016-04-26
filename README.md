#Responsive Background Image Formatter

***Beta version...***

## About

This module enables a new field display formatter for applying a responsive background image to the parent entity using the B-Lazy js plugin. The B-Lazy plugin works via device detection as well as width - so you will need to test it on a devidce, or use chrome tools to see it in action.

For more information - see the [B-Lazy](http://dinbror.dk/blog/blazy/) instructions.

At the moment you have to select 3 image sizes and these are mapped to preset breakpoints based on the Twitter Bootstrap convention.

Later the plan is to expand this to hook into the Drupal breakpoint features, also potentially to allow a choice of field or parent entity to apply to.

## Instructions

Enable the module as usual.

You will now have a new field formatter available on image fields for responsive background images.

Choose your image style for each breakpoint.

View your entity - the image from the field should now be applied as a background image to the entity wrapper element.

From there you can apply the custom layout options you need using the applied classes. There is a basic starting point included in the module.
