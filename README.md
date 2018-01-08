# Date Alert System
Add the following code to header or where ever you want alert to show. (Header will show on all pages)

```
<!-- Adding shortcode  vac-loop  -->
<?php if (($new_today>=$testtime1)&&($new_today<=$testtime2)) : ?>
<?php echo do_shortcode('[vac-loop]');?>
<?php endif; ?>
<!-- Ending shortcode  vac-loop  -->
```

Start Date and End Date Must be added as 
```
Start Date 
```
and 
```
End Date 
```
in custom fields.
Dates must be added in this format EXAMPLE: 
```
01/08/2018
```
