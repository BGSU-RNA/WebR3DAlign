var Util = (function($) {
    var my = {},
             urls = {
                        getStructureInfo:        'http://rna.bgsu.edu/rna3dhub/apiv1/get_structure_info/',
                        getEquivalentStructures: 'http://rna.bgsu.edu/rna3dhub/apiv1/get_equivalent_structures/',
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

        $(data.div + '_fragments').children().remove();
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
            data.pdbId = pdbId.toUpperCase();
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

        // clear previously loaded info
        $(data.div).children().remove();
        $(data.div).append(text);

        // enable popovers on the newly created elements
        $('.' + my.popoverClass).click(LookUpPDBInfo);
    }

    my.loadStructureData = function(div, pdbId)
    {
        my.updateFragmentSelection(div, pdbId);
        my.getEquivalentStructures(div, pdbId);

        $(div).slideDown('slow');

        $("#submit_btn").removeClass('disabled').prop('disabled', '');
    }

    return my;

}(jQuery));

var DefaultParameters = {
    small: [
        null,
        {
            d: 0.5,
            p: 9,
            b: 50,
            enabled: true
        },
        {
            enabled: false
        },
        {
            enabled: false
        }
    ],
    medium: [
        null,
        {
            d: 0.5,
            p: 2,
            b: 100,
            enabled: true
        },
        {
            d: 0.5,
            p: 9,
            b: 20,
            enabled: true
        },
        {
            enabled: false
        }
    ],
    large: [
        null,
        {
            d: 0.4,
            p: 1,
            b: 200,
            enabled: true
        },
        {
            d: 0.5,
            p: 3,
            b: 70,
            enabled: true
        },
        {
            d: 0.5,
            p: 9,
            b: 20,
            enabled: true
        }
    ]
}

var Events = (function($) {

    var my = {},
        d = [null, $("#discrepancy1"), $("#discrepancy2"), $("#discrepancy3")],
        p = [null, $("#neighborhoods1"), $("#neighborhoods2"), $("#neighborhoods3")],
        b = [null, $("#bandwidth1"), $("#bandwidth2"), $("#bandwidth3")],
        t = [null, null, $("#toggle_iteration2"), $("#toggle_iteration3")];
        e = [null, $('#iteration_enabled1'), $('#iteration_enabled2'), $('#iteration_enabled3')];

    my.loadDefaultParameters = function(setId)
    {
        data = DefaultParameters[setId];

        for (i = 1; i <= 3; i++) {
            if ( data[i].enabled ) {
                d[i].val(data[i].d);
                p[i].val(data[i].p);
                b[i].val(data[i].b);
                my.enableIteration(i);
            } else {
                my.disableIteration(i);
            }
        }

    }

    my.disableIteration = function(i)
    {
        d[i].prop('disabled', 'disabled');
        p[i].prop('disabled', 'disabled');
        b[i].prop('disabled', 'disabled');
        e[i].val(0);
        if ( i == 2 ) {
            t[i+1].prop('checked', false).prop('disabled', 'disabled');
        }
    }

    my.enableIteration = function(i)
    {
        d[i].prop('disabled', '');
        p[i].prop('disabled', '');
        b[i].prop('disabled', '');
        e[i].val(1);
        if ( i == 2 ) {
            t[i+1].prop('disabled', '');
        }
    }

    my.advancedInteractions = function()
    {
        $("#toggle_iteration2").on('click', function(){
            if (this.checked) {
                my.enableIteration(2);
            } else {
                my.disableIteration(2);
                my.disableIteration(3);
            }
        });

        $("#toggle_iteration3").on('click', function(){
            if (this.checked) {
                my.enableIteration(3);
            } else {
                my.disableIteration(3);
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
                    $(".mol1").children().remove();
                } else {
                    $("#pdb2").val('');
                    $(".mol2").children().remove();
                }
            }
            parentDiv.remove();
        });
    }

    my.reset = function()
    {
        $(".mol1").children().remove();
        $(".mol2").children().remove();
        $(".mol1_fragments").children().remove();
        $(".mol2_fragments").children().remove();
        $("#pdb1").val('');
        $("#pdb2").val('');
        $("#email").val('');
        $("#message").removeClass().html('').slideUp();
        my.disableIteration(2);
        my.disableIteration(3);
        $('.progress').hide();
        my.loadDefaultParameters('large');
    }

    my.bindEvents = function()
    {
        my.advancedInteractions();
        my.addRemoveFragment();

        my.loadDefaultParameters('large');

        $("#reset").on('click', function(evt){
            evt.preventDefault();
            my.reset();
        });

        $('#parameters_small').on('click', function(){
            my.loadDefaultParameters('small');
        });

        $('#parameters_medium').on('click', function(){
            my.loadDefaultParameters('medium');
        });

        $('#parameters_large').on('click', function(){
            my.loadDefaultParameters('large');
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

    }

    return my;

}(jQuery));


var Validator = (function($) {
    var my = {},
             urls = {
                        isValidPdb:  'http://rna.bgsu.edu/rna3dhub/apiv1/is_valid_pdb/',
                        validateNts: 'http://rna.bgsu.edu/rna3dhub/apiv1/validate_nts'
                     };

    my.popoverClass = "";


    my.validate = function()
    {
        $('form').on('submit', function(e) {

            e.preventDefault();
            form = this;
            $bar = $('.bar');
            $bar.width(200);
            $bar.text('Checking input...');
            $('.progress').slideDown();

            // reset deferred valid counter
            $('#isValid').val(0);

            // preliminary simple checks
            if ( !my.checkDiscrepancy() ||
                 !my.checkBandwidth()   ||
                 !my.checkNeighborhoods() ) {
                $bar.text('Validation failed');
                return false;
            }

            my.replaceEmptyNucleotideFields();

            // construct an array of deferred objects
            deferreds = my.checkNucleotides();

            deferreds.push( my.deferredPdbValidation('#pdb1') );
            deferreds.push( my.deferredPdbValidation('#pdb2') );

            $bar.width(400);

            // when all ajax calls are completed, check the results
            $.when.apply(null, deferreds).done(function() {
                if ( my.checkDeferredValidCounter() ) {
                    $bar.text('Submitting...').width(960);
                    // call native js event to avoid re-triggering jQuery submit
                    form.submit();
                } else {
                    $bar.text('Validation failed');
                    return false;
                }
            });
        });
    }

    my.checkNucleotides = function()
    {
        var nts = $('.nt-validate'),
            deferreds = [];

        for (var i = 0; i<nts.length; i++) {
            deferreds.push( my.deferredNucleotideValidation(nts[i]) );
        }

        return deferreds;
    }

    my._incrementSuccessCounter = function()
    {
        var counter = $('#isValid');
        counter.val( parseInt(counter.val()) + 1 );
    }

    my.deferredNucleotideValidation = function(elem)
    {
        $this = $(elem);

        var nts = $this.val();
        // no need to query the server, resolve right away
        if ( nts == 'all' ) {
            my._incrementSuccessCounter();
            return jQuery.Deferred().resolve();
        }

        var i = $this.prop('name').search('1') == -1  ?  2 : 1;

        // don't validate nucleotides if the file is uploaded
        if ( $('#upload_pdb' + i).val() != "" ) {
            my._incrementSuccessCounter();
            return jQuery.Deferred().resolve();
        }

        query = {
            pdb: $('#pdb' + i).val(),
            nts: nts,
            chain: $('select[name="mol' + i + '_chains[]"]').val()
        };

        var deferred = $.ajax({
            type: 'POST',
            url: urls.validateNts,
            data: query,
            dataType: "json"
        }).success(function(data) {
            if ( !data.valid ) {
                my.showMessage('Error: ' + data.error_msg);
                console.log('Invalid ' + nts);
                $this.focus();
            } else {
                my._incrementSuccessCounter();
                console.log('Valid nucleotides ' + nts);
            }
        });

        return deferred;
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

    my.checkDeferredValidCounter = function()
    {
        // total number of nucleotide fragments + 2 pdb ids
        return $('#isValid').val() == $('.fragment').length + 2;
    }

    my.deferredPdbValidation = function(elem)
    {
        var $elem = $(elem),
            pdbId = $elem.val();

        // if a pdb file was uploaded, don't query the server
        var i = $elem.attr('id').search('1') == -1  ?  2 : 1;
        if ( $('#upload_pdb' + i).val() != "" ) {
            $elem.val(""); // clear any pdb ids
            my._incrementSuccessCounter();
            return jQuery.Deferred().resolve();
        }

        // no need to query the server, fail right away
        if ( $.type(pdbId) !== "string" || pdbId.length != 4) {
            $bar.text('Validation failed');
            my.showMessage('Please choose a valid pdb file.');
            $elem.focus();
            return jQuery.Deferred().resolve();
        }

        var deferred = $.ajax({
            url: urls.isValidPdb + pdbId,
            dataType: "json"
        }).done(function(data) {
            if ( data.valid ) {
                my._incrementSuccessCounter();
            } else {
                message = "Pdb " + $elem.data('structure') + ' is invalid';
                $elem.focus();
                my.showMessage(message);
            }
        });

        return deferred;
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
        return my.isValidNumericValue({type: 'neighborhoods', min: 1, max: 10});
    }

    my.checkDiscrepancy = function()
    {
        return my.isValidNumericValue({type: 'discrepancy', min: 0, max: 1.0});
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

    // r3dalign or r3dalign_dev
    var environment = window.location.href.split('/')[3];

    my.url_results = 'http://rna.bgsu.edu/' + environment + '/results/';

    my._set_results_url = function(query_id)
    {
        var $results = $('#message'),
            a = '<a href="' + my.url_results + query_id + '">View precomputed results</a>';

        $results.removeClass().addClass('alert alert-success').children().remove();
        $results.html('').append(a).slideDown();
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

        Events.loadDefaultParameters('large');

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
	    $("#submit_btn").focus();
    }

    my.rrna_5s_partial = function()
    {
        Events.reset();

        Util.loadStructureData(".mol1", '2AW4');
        Util.loadStructureData(".mol2", '2J01');

	    $("#discrepancy1").val("0.5");
	    $("#neighborhoods1").val("7");
	    $("#bandwidth1").val("50");

        Events.loadDefaultParameters('small');

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
	    $("#submit_btn").focus();
    }

    my.rrna_5s_complete = function()
    {
        Events.reset();

        Util.loadStructureData(".mol1", '2AW4');
        Util.loadStructureData(".mol2", '2J01');

        Events.loadDefaultParameters('small');

        my._set_results_url('4d24dbcf8fbfb');

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
	    $("#submit_btn").focus();
    }

    my.bindEvents = function()
    {
        $("#rrna_16s").on('click', my.rrna_16s);
        $("#rrna_5s_partial").on('click', my.rrna_5s_partial);
        $("#rrna_5s_complete").on('click', my.rrna_5s_complete);
    }


    return my;

 }(jQuery));
