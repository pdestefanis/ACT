function datepick(field_id,date_start,date_end){
    jQuery('#'+field_id)
        .datePicker(
            {
                createButton:false,
                startDate:date_start,
                endDate:date_end

            }
        ).bind(
            'click',
            function()
            {
                updateSelects(jQuery(this).dpGetSelected()[0],jQuery(this).attr("id"));
                jQuery(this).dpDisplay();
                return false;
            }
        ).bind(
            'dateSelected',
            function(e, selectedDate, $td, state)
            {
                updateSelects(selectedDate,jQuery(this).attr("id"));
            }
        ).bind(
            'dpClosed',
            function(e, selected)
            {
                updateSelects(selected[0],jQuery(this).attr("id"));
            }
        );
    var updateSelects = function (selectedDate)
    {
        var selectedDate = new Date(selectedDate);
                if (selectedDate.getDate()<10){
                    jQuery('#'+field_id+'Day option[value=0' + selectedDate.getDate() + ']').attr('selected', 'selected');
                } else {
                    jQuery('#'+field_id+'Day option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
                }
                if (selectedDate.getMonth()<9){
                    jQuery('#'+field_id+'Month option[value=0' + (selectedDate.getMonth()+1) + ']').attr('selected', 'selected');
                } else {
                    jQuery('#'+field_id+'Month option[value=' + (selectedDate.getMonth()+1) + ']').attr('selected', 'selected');
                }
        jQuery('#'+field_id+'Year option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
    }

    jQuery('#'+field_id+'Day, #'+field_id+'Month, #'+field_id+'Year')
        .bind(
            'change',
            function()
            {
                var d = new Date(
                            jQuery('#'+field_id+'Year').val(),
                            jQuery('#'+field_id+'Month').val()-1,
                            jQuery('#'+field_id+'Day').val()
                        );
                jQuery('#'+field_id).dpSetSelected(d.asString());
            }
        );
    
    jQuery('#'+field_id+'Day').trigger('change');

    // Can i use drop?
    jQuery('#'+field_id+'_drop').bind(
        'click',
        function()
        {
            jQuery('#'+field_id+'Year').val("");
            jQuery('#'+field_id+'Month').val("");
            jQuery('#'+field_id+'Day').val("");
        }
    );
} 