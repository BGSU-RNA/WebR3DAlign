var UTIL = (function($) {
    var my = {},
             urls = {
                        get_structure_info: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_structure_info/',
                        get_equivalent_structures: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_equivalent_structures/',
                        equivalence_class: 'http://rna.bgsu.edu/rna3dhub/nrlist/view/',
                        rna3dhub_pdb: 'http://rna.bgsu.edu/rna3dhub/pdb/',
                        pdb_img: 'http://www.pdb.org/pdb/images/',
                        results: 'http://rna.bgsu.edu/r3dalign_dev/results/'
                     };

    my.popover_class = "pdb_info";

    my.nodes = {
                    iteration1: $('#iteration1'),
                    iteration2: $('#iteration2'),
                    iteration3: $('#iteration3'),


                };

//     my.dom.examples = {rnase_p: '#rnase_p'};


    my.create_select_dropdown_for_chains = function(data)
    {
        // get existing selects
        var selects = $(data.div + ' select');

        // create wrapper div
        var d = $("<div>", {
            class: 'form-inline fragment',
        });

        // create new select
        var s = $("<select />", {
            name: data.div.replace('.', '') + '_chain' + selects.length,
            id:   data.div.replace('.', '') + '_chain' + selects.length
        });
        $.each(data.rna_compounds, function(key, value){
            $("<option />", {
                value: value.chain,
                text: 'Chain ' + value.chain + ': ' + value.compound + ' (' + value.length + ' nts)'
            }).appendTo(s);
        });
        s.appendTo(d);

        // nucleotides input
        var i = $("<input>", {
            type: 'text',
            placeholder: 'nucleotides',
            class: 'input-medium',
            name: data.div.replace('.', '') + '_nts' + selects.length,
            id:   data.div.replace('.', '') + '_nts' + selects.length
        }).appendTo(d);

        // plus and minus button group
        var buttons = $("<div class='btn-toolbar'><div class='btn-group'><a class='btn btn-small plus-fragment' href='#'><i class='icon-plus'></i></a><a class='btn btn-small minus-fragment' href='#'><i class='icon-minus'></i></a></div></div>")
                      .appendTo(d);
        d.appendTo(data.div + "_fragments");
    }

    my.update_fragment_selection = function(div, pdb_id)
    {
        var url = urls.get_structure_info + pdb_id;
        $.get(url, function(data) {
            data.pdb_id = pdb_id;
            data.div = div;
            my.create_select_dropdown_for_chains(data);
        }, "json");
    }

    my.get_similar_structures = function(div, pdb_id)
    {
        var url = urls.get_equivalent_structures + pdb_id;
        $.get(url, function(data) {
            data.div = div;
            data.pdb_id = pdb_id;
            my.update_similar_structures_template(data);
        }, "json");
    }

    my.generate_no_equivalence_class_text = function(data)
    {
        return $('<div>', {
            class : "fade in alert alert-important"
        }).append('<span class="label label-info">Redundancy report</span> ')
          .append('<a class="' + my.popover_class + '">' + data.pdb_id + '</a> ' +
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
            class: "fade in ",
        }).append('<span class="label label-info">Redundancy report</span> ')
          .append('<a class="' + my.popover_class + '">' + data.pdb_id + '</a>')
          .append(' represents ')
          .append('<a target="_blank" href="' + urls.equivalence_class + data.eq_class +
                  '">' + data.related_pdbs.length + ' structures</a>')
          .append(', including ')
          .append(links.join(', ') + '.');
    }

    my.generate_is_single_member_text = function(data)
    {
        return $('<div>', {
            class : "fade in "
        }).append('<span class="label label-info">Redundancy report</span> ')
          .append('<a class="' + my.popover_class + '">' + data.pdb_id + '</a>')
          .append(' is a single member of ')
          .append('<a target="_blank" href="' + urls.equivalence_class + data.pdb_id + '">' +
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
                class: "fade in "
        }).append('<span class="label label-info">Redundancy report</span> ')
          .append('<a class="' + my.popover_class + '">' + data.pdb_id + '</a>')
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
                class: "fade in "
        }).append('<span class="label label-info">Redundancy report</span> ')
          .append('<a class="' + my.popover_class + '">' + data.pdb_id + '</a>')
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

        } else if ( data.pdb_id == data.representative && data.related_pdbs.length > 0 ) {
            text = my.generate_is_representative_text(data);

        } else if ( data.related_pdbs.length == 0 ) {
            text = my.generate_is_single_member_text(data);

        } else if ( data.related_pdbs.length == 1 ) {
            text = my.generate_is_the_only_other_member_text(data);

        } else {
            text = my.generate_is_regular_member_text(data);
        }

        $(data.div).append(text);

        // enable popovers
        $('.' + my.popover_class).click(LookUpPDBInfo);
    }

    my.load_structure_data = function(div, pdb_id)
    {
        // clear fragments
        $(div + '_fragments').children().remove();

        // clear previously loaded tips
        $(div).children().remove();

        my.update_fragment_selection(div, pdb_id);
        my.get_similar_structures(div, pdb_id);

        $(div).show();
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
        $(".plus-fragment").live("click", function(evt){
            evt.preventDefault();
            var parent_div = $(this).parents('.fragment');
            var clone = parent_div.clone();

            // TODO
            clone.children()
                 .filter('select')
                 .attr('id', 'test')
                 .attr('name', 'test');

            clone.children()
                 .filter('input')
                 .attr('id', 'test2')
                 .attr('name', 'test2');

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
            $(".mol1").children().remove();
            $(".mol2").children().remove();
            $(".mol1_fragments").children().remove();
            $(".mol2_fragments").children().remove();
            my.select_pdb_id('.pdb1', '');
            my.select_pdb_id('.pdb2', '');
            $("#email").val('');
            $(".results").hide();
         });
    }

    my.events_examples = function()
    {
        $("#rnase_p").on('click', my.examples_rnase_p);

    }

    my.bind_events = function()
    {
        my.events_advanced_interactions();
        my.events_plus_minus_fragments();
        my.events_reset();
        my.events_examples();
    }

    my.select_pdb_id = function(target, pdb_id)
    {
        var index = 0,
            $target = $(target);

        if ( pdb_id != '' ) {
            $(target).children().each(function(i, option) {
                if ( pdb_id == option.value ) {
                    index = i;
                    return false;
                }
            });
        }

        $target[0].selectedIndex = index;
        $target.trigger("liszt:updated");
        return index;
    }

    my.examples_rnase_p = function()
    {
	    $("#discrepancy1").val("0.5");
	    $("#neighborhoods1").val("3");
	    $("#bandwidth1").val("200");

	    $("#discrepancy2").val("0.5");
	    $("#neighborhoods2").val("9");
	    $("#bandwidth2").val("80");

        $("#clique_method_full1").prop('checked', true);
        $("#clique_method_full2").prop('checked', true);

        $("#toggle_iteration2").prop('checked', true);
        $("#toggle_advanced").html('Hide advanced options');
        $(".advanced-options").show();
        $("#iteration1").show();
        $("#iteration2").show();
        $("#iteration3").hide();

        $(".results").children().remove();
        $(".results").append('<a href="' + urls.results + '4d1269ba996fc">View precomputed results</a>')
                     .show();

        my.select_pdb_id('.pdb1', '1U9S');
        my.load_structure_data(".mol1", '1U9S');
        // use chain A

        my.select_pdb_id('.pdb2', '1NBS');
        // select chain B
        $('.mol2').ajaxComplete(function() {
            $('#mol2_chain0').children().filter('select')[0].selectedIndex = 1;
        });
        my.load_structure_data(".mol2", '1NBS');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').focus();
    }

    return my;
}(jQuery));
