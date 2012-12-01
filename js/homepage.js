var Util = (function($) {
    var my = {},
             urls = {
                        getStructureInfo:        'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_structure_info/',
                        getEquivalentStructures: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_equivalent_structures/',
                        equivalenceClass:        'http://rna.bgsu.edu/rna3dhub/nrlist/view/',
                        rna3dhubPdb:             'http://rna.bgsu.edu/rna3dhub/pdb/'
                     };

    my.popoverClass = "pdb_info";


    my.createSelectDropdownForChains = function(data)
    {
        data.chainSelectName = data.div.replace('.', '') + '_chains[]';
        data.ntsInputName = data.div.replace('.', '') + '_nts[]';

        var source   = $("#chain-fragment").html();
        var template = Handlebars.compile(source);
        var html = template(data);

        $(data.div + "_fragments").append(html);

        $('.fragment .icon-question-sign').tooltip();
    }

    my.updateFragmentSelection = function(div, pdbId)
    {
        var url = urls.getStructureInfo + pdbId;
        $.get(url, function(data) {
            data.pdbId = pdbId;
            data.div = div;
            my.createSelectDropdownForChains(data);
        }, "json");
    }

    my.getEquivalentStructures = function(div, pdbId)
    {
        var url = urls.getEquivalentStructures + pdbId;
        $.get(url, function(data) {
            data.div = div;
            data.pdbId = pdbId;
            my.updateEquivalentStructuresTemplate(data);
        }, "json");
    }

    my.generateNoEquivalenceClassMessage = function(data)
    {
        var source   = $("#no-equivalence-class").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generateIsRepresentativeMessage = function(data)
    {
        var source   = $("#representative-structure").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generateSingleMemberMessage = function(data)
    {
        var source   = $("#single-member").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generateRegularMemberMessage = function(data)
    {
        // remove the first element, which is the representative
        data.related_pdbs = data.related_pdbs.slice(1, data.related_pdbs.length);
        data.numStructures = data.related_pdbs.length;
        data.manyStructures = data.numStructures == 1 ? false : true;

        var source   = $("#regular-member").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.generateOneOfTwoMembersMessage = function(data)
    {
        var source   = $("#the-only-other-member").html();
        var template = Handlebars.compile(source);
        return template(data);
    }

    my.updateEquivalentStructuresTemplate = function(data)
    {
        var text = '';

        data.url = urls.equivalenceClass + data.eq_class;
        data.popoverClass = my.popoverClass;
        data.numStructures = data.related_pdbs.length;

        if ( data.related_pdbs.length == 0 && data.representative == null) {
            text = my.generateNoEquivalenceClassMessage(data)

        } else if ( data.pdbId == data.representative && data.related_pdbs.length > 0 ) {
            text = my.generateIsRepresentativeMessage(data);

        } else if ( data.related_pdbs.length == 0 ) {
            text = my.generateSingleMemberMessage(data);

        } else if ( data.related_pdbs.length == 1 ) {
            text = my.generateOneOfTwoMembersMessage(data);

        } else {
            text = my.generateRegularMemberMessage(data);
        }

        $(data.div).append(text);

        // enable popovers on the newly created elements
        $('.' + my.popoverClass).click(LookUpPDBInfo);
    }

    my.loadStructureData = function(div, pdbId)
    {
        // clear fragments
        $(div + '_fragments').children().remove();

        // clear previously loaded structure info
        $(div).children().remove();

        my.updateFragmentSelection(div, pdbId);
        my.getEquivalentStructures(div, pdbId);

        $(div).slideDown('slow');
    }

    return my;

}(jQuery));


var Events = (function($) {

    var my = {};

    my.advancedInteractions = function()
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

    my.addRemoveFragment = function()
    {
        $(".plus-fragment").live("click", function(evt){
            evt.preventDefault();
            var parentDiv = $(this).parents('.fragment');
            var clone = parentDiv.clone();

            clone.find('input').val('');

            parentDiv.parent().append(clone);
            $('.fragment .icon-question-sign').tooltip();
        });

        $(".minus-fragment").live("click", function(e){
            e.preventDefault();
            var parentDiv = $(this).parents('.fragment');
            if ( parentDiv.siblings().filter('.fragment').length == 0 ) {
                if (parentDiv.parents('.mol1_fragments').length != 0 ) {
                    $("#pdb1").val('');
                } else {
                    $("#pdb2").val('');
                }
            }
            parentDiv.remove();
        });
    }

    my.resetAdvancedParameters = function(iterationId)
    {
        $('#neighborhoods' + iterationId).val(7);
        $('#discrepancy' + iterationId).val(0.5);
        $('#bandwidth' + iterationId).val(60);
        $('#clique_method_greedy' + iterationId).attr('checked', true);
        if ( iterationId == 1 ) {
            $('#seed_default').attr('checked', true);
            $('#seed_upload_file').remove();
            $('#seed_upload_toggle').parent()
                                    .after('<input type="file" ' +
                                                  'name="seed_upload_file" ' +
                                                  'id="seed_upload_file" ' +
                                                  'size="20" disabled/>');
        }
    }

    my.reset = function()
    {
        $(".advanced-options").slideUp();
        $(".mol1").children().remove();
        $(".mol2").children().remove();
        $(".mol1_fragments").children().remove();
        $(".mol2_fragments").children().remove();
        $("#pdb1").val('');
        $("#pdb2").val('');
        $("#email").val('');
        $("#message").removeClass().hide();
        $("#iteration2").hide();
        $("#iteration3").hide();
        my.resetAdvancedParameters(1);
        my.resetAdvancedParameters(2);
        my.resetAdvancedParameters(3);
    }

    my.bind_events = function()
    {
        my.advancedInteractions();
        my.addRemoveFragment();

        $("#reset").on('click', function(evt){
            evt.preventDefault();
            my.reset();
        });

        $('.typeahead').on('change', function(){
            $this = $(this);

            if ( $this.val().length != 4 ) {
                return;
            }

            var div = ".mol" + $this.data('structure');
            var pdbId = $this.val();
            Util.loadStructureData(div, pdbId);
        });

        $('#seed_upload_toggle').click(function(){
            $('#seed_upload_file').prop('disabled', '');
        });
        $('#seed_default').click(function(){
            $('#seed_upload_file').prop('disabled', 'disabled');
        });

        $('.reset-advanced').on('click', function(evt){
            evt.preventDefault();
            my.resetAdvancedParameters($(this).data('iteration'));
        });

    }

    return my;

}(jQuery));


var Validator = (function($) {
    var my = {},
             urls = {
                        isValidPdb: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/is_valid_pdb/'
                     };

    my.popoverClass = "";


    my.markIterations = function(data)
    {
        for (var i = 1; i <= 3; i++) {
            if ( $("#iteration" + i).is(":visible") ) {
                $("#iteration_enabled" + i).val(1);
            } else {
                $("#iteration_enabled" + i).val(0);
            }
        }
    }

    my.replaceEmptyNucleotideFields = function()
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

    my.isValidPdb = function(pdbId){
        return $.get(urls.isValidPdb + pdbId, function(data) {
            if ( data.valid ) {
                return true;
            } else {
                return false;
            }
        }, "json");
    }

    my.checkPdbId = function(elem)
    {
        var $elem = $(elem),
            pdbId = $elem.val();

        if ( $.type(pdbId) === "string" && pdbId.length == 4 && my.isValidPdb(pdbId) ) {
            return true;
        } else {
            message = "Pdb " + $elem.data('structure') + ' is invalid';
            $elem.focus();
            my.showMessage(message);
            return false;
        }
    }

    my.showMessage = function(message)
    {
        $('#message').removeClass()
                     .addClass('alert alert-error')
                     .html(message)
                     .slideDown();
    }

    my.checkNeighborhoods = function()
    {
        return my.isValidNumericValue({type: 'neighborhoods', min: 2, max: 10});
    }

    my.checkDiscrepancy = function()
    {
        return my.isValidNumericValue({type: 'discrepancy', min: 0, max: 0.7});
    }

    my.checkBandwidth = function()
    {
        return my.isValidNumericValue({type: 'bandwidth', min: 1, max: 10000});
    }

    my.isValidNumericValue = function(data)
    {
        for (var i = 1; i <= 3; i++) {
            if ( !$("#iteration" + i).is(':visible') ) {
                continue;
            }
            var elem = $("#" + data.type + i);
            var value = elem.val();
            if ( !$.isNumeric(value) || value < data.min || value > data.max ) {
                var message = data.type + ' in iteration ' + i +
                              ' should be between ' + data.min +
                              ' and ' + data.max;
                my.showMessage(message);
                elem.focus();
                return false;
            }
        }
        return true;
    }


    return my;

}(jQuery));


var Examples = (function($) {

    var my = {};

    my.url_results = 'http://rna.bgsu.edu/r3dalign_dev/results/';


    my._set_results_url = function(query_id)
    {
        var $results = $('#message'),
            a = '<a href="' + my.url_results + query_id + '">View precomputed results</a>';

        $results.removeClass().addClass('alert alert-success').html('').children().remove();
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
        Events.reset();

        Util.loadStructureData(".mol1", '1U9S');
        Util.loadStructureData(".mol2", '1NBS');

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

        $("#pdb1").val('1U9S');
        $("#pdb2").val('1NBS');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').prop('disabled', '').focus();
    }

    my.rrna_16s = function()
    {
        Events.reset();

        Util.loadStructureData(".mol1", '1J5E');
        Util.loadStructureData(".mol2", '2AVY');

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

        $("#pdb1").val('1J5E');
        $("#pdb2").val('2AVY');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').prop('disabled', '').focus();
    }

    my.rrna_5s_partial = function()
    {
        Events.reset();

        Util.loadStructureData(".mol1", '2AW4');
        Util.loadStructureData(".mol2", '2J01');

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

        $("#pdb1").val('2AW4');
        $("#pdb2").val('2J01');

	    $("#email").val("");
	    $("#submit").removeClass('disabled').prop('disabled', '').focus();
    }

    my.rrna_5s_complete = function()
    {
        Events.reset();

        Util.loadStructureData(".mol1", '2AW4');
        Util.loadStructureData(".mol2", '2J01');

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

        $("#pdb1").val('2AW4');
        $("#pdb2").val('2J01');

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
