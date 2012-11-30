var Util = (function($) {
    var my = {},
             urls = {
                        get_structure_info: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_structure_info/',
                        get_equivalent_structures: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_equivalent_structures/',
                        equivalence_class: 'http://rna.bgsu.edu/rna3dhub/nrlist/view/',
                        rna3dhub_pdb: 'http://rna.bgsu.edu/rna3dhub/pdb/',
                        pdb_img: 'http://www.pdb.org/pdb/images/'
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
//             id:   data.div.replace('.', '') + '_chains' + selects.length,
            name: data.div.replace('.', '') + '_chains[]',
            class: "span4"
        });
        $.each(data.rna_compounds, function(key, value){
            $("<option />", {
                value: value.chain,
                text: 'Chain ' + value.chain + ': ' + value.compound + ' (' + value.length + ' nts)'
            }).appendTo(s);
        });
        s.appendTo(d);

        // nucleotides input

        var help = 'Enter nucleotides to align.<br>Ranges can be specified using a colon<br> and can be separated by commas.<br>Example: 2:20,62:69,110:119';

        var nts = $('<div class="input-append input-prepend">' +
          '<span class="add-on"><i class="icon-question-sign" data-html="true" data-original-title="' + help + '"></i></span>' +
          '<input class="input-medium" name="' +
          data.div.replace('.', '') + '_nts[]' + '" type="text" placeholder="leave blank to use all">' +
          '<button class="btn minus-fragment" type="button"><i class="icon-minus-sign"></i></button>' +
          '<button class="btn plus-fragment" type="button"><i class="icon-plus-sign"></i></button>' +
        '</div>').appendTo(d);

        d.appendTo(data.div + "_fragments");

        $('.fragment .icon-question-sign').tooltip();
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
        var source   = $("#no-equivalence-class").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generate_is_representative_text = function(data)
    {
        var source   = $("#representative-structure").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generate_is_single_member_text = function(data)
    {
        var source   = $("#single-member").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generate_is_regular_member_text = function(data)
    {
        // remove the first element, which is the representative
        data.related_pdbs = data.related_pdbs.slice(1, data.related_pdbs.length);
        data.numStructures = data.related_pdbs.length;
        data.manyStructures = data.numStructures == 1 ? false : true;

        var source   = $("#regular-member").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generate_is_the_only_other_member_text = function(data)
    {
        var source   = $("#the-only-other-member").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.update_similar_structures_template = function(data)
    {
        var text = '';

        data.url = urls.equivalence_class + data.eq_class;
        data.popoverClass = my.popover_class;
        data.numStructures = data.related_pdbs.length;

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

        $(div).slideDown();
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

            clone.find('input').val('');

            parent_div.parent().append(clone);
            $('.fragment .icon-question-sign').tooltip();
        });

        $(".minus-fragment").live("click", function(e){
            e.preventDefault();
            var parent_div = $(this).parents('.fragment');
            if ( parent_div.siblings().filter('.fragment').length == 0 ) {
                if (parent_div.parents('.mol1_fragments').length != 0 ) {
                    $('.pdb1')[0].selectedIndex = 0;
                    $('.pdb1').trigger("liszt:updated");
                } else {
                    $('.pdb2')[0].selectedIndex = 0;
                    $('.pdb2').trigger("liszt:updated");
                }
            }
            parent_div.remove();
        });
    }

    my.reset = function()
    {
        $(".mol1").children().remove();
        $(".mol2").children().remove();
        $(".mol1_fragments").children().remove();
        $(".mol2_fragments").children().remove();
        my.set_pdb_ids('', '');
        $("#email").val('');
        $(".results").hide();
    }

    my.events_reset = function()
    {
        $("#reset").on('click', my.reset);
    }

    my.bind_events = function()
    {
        my.events_advanced_interactions();
        my.events_plus_minus_fragments();
        my.events_reset();
    }

    my.set_pdb_ids = function(pdb_id1, pdb_id2)
    {
        var index1 = 0,
            index2 = 0,
            target1 = '.pdb1',
            target2 = '.pdb2',
            $target1 = $(target1),
            $target2 = $(target2);

        if ( pdb_id1 != '' && pdb_id2 != '' ) {
            $target1.children().each(function(i, option) {
                if ( pdb_id1 == option.value ) {
                    index1 = i;
                } else if ( pdb_id2 == option.value ) {
                    index2 = i;
                }
                // break from the loop when both indexes are found
                if ( index1 != 0 && index2 != 0 ) {
                    return false;
                }
            });
        }

        $target1[0].selectedIndex = index1;
        $target1.trigger("liszt:updated");
        $target2[0].selectedIndex = index2;
        $target2.trigger("liszt:updated");
    }

    return my;

}(jQuery));



var Validator = (function($) {
    var my = {},
             urls = {
                        get_structure_info: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_structure_info/',
                        get_equivalent_structures: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_equivalent_structures/',
                     };

    my.popover_class = "";


    my.check_iterations = function(data)
    {
        for (var i = 1; i <= 3; i++) {
            if ( $("#iteration" + i).is(":visible") ) {
                $("#iteration_enabled" + i).val(1);
            } else {
                $("#iteration_enabled" + i).val(0);
            }
        }
    }

    my.replace_empty_nucleotide_fields = function()
    {
        for (var i = 1; i <= 2; i++) {
            $("input[name='mol" + i + "_nts[]']").each(function(){
                $this = $(this);
                if ( $this.val() == '' ) {
                    $this.val('all');
                }
            });
        }
    }

    return my;

}(jQuery));


var Examples = (function($) {

    var my = {};

    my.url_results = 'http://rna.bgsu.edu/r3dalign_dev/results/';


    my._set_results_url = function(query_id)
    {
        var $results = $('.results'),
            a = '<a href="' + my.url_results + query_id + '">View precomputed results</a>';

        $results.children().remove();
        $results.append(a).show();
    }

    my._set_nucleotides = function(mol, nts)
    {
        var selector = 'input[name="' + mol + '_nts[]"]';
        if ( $(selector).length > 0 ) {
            $(selector).first().val(nts);
        }
    }

    my._set_chain = function(mol, chain)
    {
        var id = 'select[name="' + mol + '_chains[]"]',
            index = -1;
        if ( $(id).length > 0 ) {
            $(id).children().each(function(i, option) {
                if ( chain == option.value ) {
                    index = i;
                    return false;
                }
            });
            $(id)[0].selectedIndex = index;
        }
    }

    my.rnase_p = function()
    {
        Util.reset();

        Util.load_structure_data(".mol1", '1U9S');
        Util.load_structure_data(".mol2", '1NBS');

	    $("#discrepancy1").val("0.5");
	    $("#neighborhoods1").val("3");
	    $("#bandwidth1").val("200");

	    $("#discrepancy2").val("0.5");
	    $("#neighborhoods2").val("9");
	    $("#bandwidth2").val("80");

        $("#clique_method_full1").prop('checked', true);
        $("#clique_method_full2").prop('checked', true);

        if ( !$('#iteration1').is(':visible') ) {
            $('#toggle_advanced').trigger('click');
        }
        $("#iteration2").show();
        $("#iteration3").hide();
        $("#toggle_iteration2").prop('checked', true);
        $("#toggle_iteration3").prop('checked', false);

        my._set_results_url('4d1269ba996fc');

        $('.mol1').ajaxComplete(function() {
            my._set_chain('mol1', 'A');
            my._set_nucleotides('mol1', 'all');
        });

        $('.mol2').ajaxComplete(function() {
            my._set_chain('mol2', 'B');
            my._set_nucleotides('mol2', 'all');
        });

        my._set_pdb_ids('1U9S', '1NBS');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').prop('disabled', '').focus();
    }

    my.rrna_16s = function()
    {
        Util.reset();

        Util.load_structure_data(".mol1", '1J5E');
        Util.load_structure_data(".mol2", '2AVY');

	    $("#discrepancy1").val("0.5");
	    $("#neighborhoods1").val("3");
	    $("#bandwidth1").val("60");

	    $("#discrepancy2").val("0.5");
	    $("#neighborhoods2").val("9");
	    $("#bandwidth2").val("20");

        $("#clique_method_full1").prop('checked', true);
        $("#clique_method_greedy2").prop('checked', true);

        if ( !$('#iteration1').is(':visible') ) {
            $('#toggle_advanced').trigger('click');
        }
        $("#iteration2").show();
        $("#iteration3").hide();
        $("#toggle_iteration2").prop('checked', true);
        $("#toggle_iteration3").prop('checked', false);

        my._set_results_url('4d24d95bee03d');

        $('.mol1').ajaxComplete(function() {
            my._set_chain('mol1', 'A');
            my._set_nucleotides('mol1', 'all');
        });

        $('.mol2').ajaxComplete(function() {
            my._set_chain('mol2', 'A');
            my._set_nucleotides('mol2', 'all');
        });

        Util.set_pdb_ids('1J5E', '2AVY');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').prop('disabled', '').focus();
    }

    my.rrna_5s_partial = function()
    {
        Util.reset();

        Util.load_structure_data(".mol1", '2AW4');
        Util.load_structure_data(".mol2", '2J01');

	    $("#discrepancy1").val("0.5");
	    $("#neighborhoods1").val("7");
	    $("#bandwidth1").val("50");

        $("#clique_method_greedy1").prop('checked', true);

        if ( !$('#iteration1').is(':visible') ) {
            $('#toggle_advanced').trigger('click');
        }
        $("#iteration2").hide();
        $("#iteration3").hide();
        $("#toggle_iteration2").prop('checked', false);
        $("#toggle_iteration3").prop('checked', false);

        my._set_results_url('4d24dbc864984');

        $('.mol1').ajaxComplete(function() {
            my._set_chain('mol1', 'A');
            my._set_nucleotides('mol1', '2:20,62:69,109:118');
        });

        $('.mol2').ajaxComplete(function() {
            my._set_chain('mol2', 'B');
            my._set_nucleotides('mol2', '2:20,62:69,110:119');
        });

        Util.set_pdb_ids('2AW4', '2J01');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').prop('disabled', '').focus();
    }

    my.rrna_5s_complete = function()
    {
        Util.reset();

        Util.load_structure_data(".mol1", '2AW4');
        Util.load_structure_data(".mol2", '2J01');

        if ( !$('#iteration1').is(':visible') ) {
            $('#toggle_advanced').trigger('click');
        }
        $("#iteration2").hide();
        $("#iteration3").hide();
        $("#toggle_iteration2").prop('checked', false);
        $("#toggle_iteration3").prop('checked', false);

        my._set_results_url('4d24dbc864984');

        $('.mol1').ajaxComplete(function() {
            my._set_chain('mol1', 'A');
            my._set_nucleotides('mol1', 'all');
        });

        $('.mol2').ajaxComplete(function() {
            my._set_chain('mol2', 'B');
            my._set_nucleotides('mol2', 'all');
        });

        Util.set_pdb_ids('2AW4', '2J01');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').prop('disabled', '').focus();
    }

    my.bind_events = function()
    {
        $("#rnase_p").on('click', my.rnase_p);
        $("#rrna_16s").on('click', my.rrna_16s);
        $("#rrna_5s_partial").on('click', my.rrna_5s_partial);
        $("#rrna_5s_complete").on('click', my.rrna_5s_complete);
    }


    return my;

 }(jQuery));
