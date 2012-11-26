var UTIL = (function($) {
    var my = {},
             urls = {
                        get_structure_info: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_structure_info/',
                        get_equivalent_structures: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_equivalent_structures/',
                        equivalence_class: 'http://rna.bgsu.edu/rna3dhub/nrlist/view/',
                        rna3dhub_pdb: 'http://rna.bgsu.edu/rna3dhub/pdb/',
                        pdb_img: 'http://www.pdb.org/pdb/images/'
                     };

    my.popover_class = "pdb_info";

    my.create_select_dropdown_for_chains = function(div_id, data)
    {
        var selects = $(div_id + ' select');
        var d = $("<div class='form-inline fragment'/>");
        var s = $("<select />");
        $.each(data.rna_compounds, function(key, value){
            var text = 'Chain ' + value.chain + ': ' + value.compound + ' (' + value.length + ' nts)';
            $("<option />", {value: value.chain, text: text}).appendTo(s);
        });
        s.appendTo(d);
        var i = $("<input type='text' placeholder='nucleotides' class='input-medium'>").appendTo(d);
        var buttons = $("<div class='btn-toolbar'><div class='btn-group'><a class='btn btn-small plus-fragment' href='#'><i class='icon-plus'></i></a><a class='btn btn-small minus-fragment' href='#'><i class='icon-minus'></i></a></div></div>")
                      .appendTo(d);
        d.appendTo(div_id);
    }

    my.update_fragment_selection = function()
    {
        var url = urls.get_structure_info + my.pdb_id;
        var div = $(my.div);
        $.get(url, function(data) {
            my.create_select_dropdown_for_chains(my.div.replace('#','.') + '_fragments', data);
        }, "json");
    }

    my.get_similar_structures = function()
    {
        var url = urls.get_equivalent_structures + my.pdb_id;
        $.get(url, function(data) {
            my.update_similar_structures_template(data);
        }, "json");
    }

    my.generate_no_equivalence_class_text = function(data)
    {
        return $('<div>', {
            class : "fade in alert alert-warning"
        }).append('<a class="' + my.popover_class + '">' + my.pdb_id + '</a> ' +
                  " hasn't been included in the Non-redundant Atlas." +
                  " Either it doesnt't have any complete nucleotides, or it hasn't been processed yet.");
    }

    my.generate_is_representative_text = function(data)
    {
        // prepare the list of similar structures
        var links = $.map(data.related_pdbs, function(val) {
            return '<a class="' + my.popover_class + '">' + val + '</a>';
        });

        return $('<div>', {
                class: "fade in alert alert-info"
        }).append('<a class="close" data-dismiss="alert" href="#">&times;</a>')
          .append('<a class="' + my.popover_class + '">' + my.pdb_id + '</a>')
          .append(' represents ')
          .append('<a target="_blank" href="' + urls.equivalence_class + data.eq_class +
                  '">' + data.related_pdbs.length + ' structures</a>')
          .append(', including ')
          .append(links.join(', ') + '.');
    }

    my.generate_is_single_member_text = function(data)
    {
        return $('<div>', {
            class : "fade in alert alert-info"
        }).append('<a class="close" data-dismiss="alert" href="#">&times;</a>')
          .append('<a class="' + my.popover_class + '">' + my.pdb_id + '</a>')
          .append(' is a single member of ')
          .append('<a target="_blank" href="' + urls.equivalence_class + my.pdb_id + '">' +
                   data.eq_class + '</a>');
    }

    my.generate_is_regular_member_text = function(data)
    {
        // remove the first element, which is the representative
        data.related_pdbs = data.related_pdbs.slice(1, data.related_pdbs.length);

        // prepare the list of similar structures
        var links = $.map(data.related_pdbs, function(val) {
            return '<a class="' + my.popover_class + '">' + val + '</a>';
        });

        return $('<div>', {
                class: "fade in alert alert-info"
        }).append('<a class="close" data-dismiss="alert" href="#">&times;</a>')
          .append('<a class="' + my.popover_class + '">' + my.pdb_id + '</a>')
          .append(' is represented by ')
          .append('<a class="' + my.popover_class + '">' + data.representative + '</a>')
          .append(' along with ')
          .append('<a href="' + urls.equivalence_class +
                   data.eq_class + '" target="_blank">' + data.related_pdbs.length +
                  ' other structure' + (data.related_pdbs.length == 1 ? '' : 's') +
                  '</a>')
          .append(', including ' + links.join(', ') + '.');
    }

    my.generate_is_the_only_other_member_text = function(data)
    {
        // 1A4D is represented by 1A51, which together form NR_all_39400.1
        return $('<div>', {
                class: "fade in alert alert-info"
               }).append('<a class="close" data-dismiss="alert" href="#">&times;</a>')
                 .append('<a class="' + my.popover_class + '">' + my.pdb_id + '</a>')
                 .append(' is represented by ')
                 .append('<a class="' + my.popover_class + '">' + data.representative + '</a>')
                 .append(', which together form ')
                 .append('<a href="' + urls.equivalence_class +
                          data.eq_class + '" target="_blank">' + data.eq_class + '</a>');
    }

    my.update_similar_structures_template = function(data)
    {
        var text = '';

        if ( data.related_pdbs.length == 0 && data.representative == null) {
            text = my.generate_no_equivalence_class_text(data)

        } else if ( my.pdb_id == data.representative && data.related_pdbs.length > 0 ) {
            text = my.generate_is_representative_text(data);

        } else if ( data.related_pdbs.length == 0 ) {
            text = my.generate_is_single_member_text(data);

        } else if ( data.related_pdbs.length == 1 ) {
            text = my.generate_is_the_only_other_member_text(data);

        } else {
            text = my.generate_is_regular_member_text(data);
        }

        $(my.div).append(text);

        // enable popovers
        $('.' + my.popover_class).click(LookUpPDBInfo);
    }

    my.load_structure_data = function(div, pdb_id)
    {
        my.div = div;
        my.pdb_id = pdb_id;

        // clear fragments
        $(div.replace('#','.') + '_fragments').children().remove();

        // clear previously loaded tips
        $(div).children().remove();

        my.update_fragment_selection();
        my.get_similar_structures();

        $(my.div).show();
    }

    my.events_advanced_interactions = function()
    {
        $("#toggle_advanced").toggle(function(){
            $(this).html('Hide advanced options');
            $('.advanced-options').slideDown();
        }, function(){
            $(this).html('Show advanced options');
            $('.advanced-options').slideUp();
        });

        $("#toggle_iteration2").on('click', function(){
            if ( this.checked ) {
                $("#iteration2").slideDown();
            } else {
                $("#iteration2").slideUp();
                $("#iteration3").slideUp();
                $("#toggle_iteration3").prop('checked', false);
            }
        });

        $("#toggle_iteration3").on('click', function(){
            if ( this.checked ) {
                $("#iteration3").slideDown();
            } else {
                $("#iteration3").slideUp();
            }
        });
    }

    my.events_plus_minus_fragments = function()
    {
        $(".plus-fragment").live("click", function(e){
            event.preventDefault();
            var parent_div = $(this).parents('.fragment');
            var clone = parent_div.clone();
            parent_div.parent().append(clone);
        });

        $(".minus-fragment").live("click", function(e){
            e.preventDefault();
            $(this).parents('.fragment').remove();
        });
    }

    my.events_reset = function()
    {
        $("#reset").on('click', function(){
            $("#mol1_info").hide();
            $("#mol2_info").hide();
            $(".mol1_info_fragments").children().remove();
            $(".mol2_info_fragments").children().remove();
            $(".pdb1").selectedIndex = 0;
            $(".pdb2").selectedIndex = 0;
         });
    }

    my.bind_events = function()
    {
        my.events_advanced_interactions();
        my.events_plus_minus_fragments();
        my.events_reset();
    }

    return my;
}(jQuery));
