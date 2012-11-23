var UTIL = (function($) {
    var my = {},
             urls = {
                        get_structure_info: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_structure_info/',
                        get_equivalent_structures: 'http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_equivalent_structures/',
                        equivalence_class: 'http://rna.bgsu.edu/rna3dhub/nrlist/view/',
                        rna3dhub_pdb: 'http://rna.bgsu.edu/rna3dhub/pdb/'
                     };

    my.publicVariable = 2;

    my.create_select_dropdown_for_chains = function(div_id, data)
    {
        var selects = $(div_id + ' select');
        var s = $("<select />");
        $.each(data.rna_compounds, function(key, value){
            var text = 'Chain ' + value.chain + ': ' + value.compound + ' (' + value.length + ' nts)';
            $("<option />", {value: value.chain, text: text}).appendTo(s);
        });
        s.appendTo(div_id);
    }

    my.get_pdb_info = function(pdb_id, div_id)
    {
        var url = urls.get_structure_info + pdb_id;
        var div = $(div_id);
        $.get(url, function(data) {
            data.pdb_id = pdb_id;
            my.update_pdb_info_template(div_id, data);
        }, "json");
    }

    my.update_pdb_info_template = function(div_id, data)
    {
        $(div_id + '_title').html(data.structureTitle);
        $(div_id + '_resolution').html(data.resolution);
        $(div_id + '_technique').html(data.experimentalTechnique);
        $(div_id + '_ndb_link').attr('href', data.ndb_url);
        $(div_id + '_pdb_link').attr('href', data.pdb_url);
        $(div_id + '_rna3dhub_link').attr('href', urls.rna3dhub_pdb + data.pdb_id);
        my.create_select_dropdown_for_chains(div_id + '_fragments', data);
    }

    my.get_similar_structures = function(pdb_id, div_id)
    {
        var url = urls.get_equivalent_structures + pdb_id;
        var div = $(div_id);
        $.get(url, function(data) {
            my.update_similar_structures_template(div_id, data);
        }, "json");
    }

    my.update_similar_structures_template = function(div_id, data)
    {
        var similar_ids = '';
        var cutoff = 30;
        if (data.related_pdbs.length == 0) {
            similar_ids = 'None found';
        } else if (data.related_pdbs.length > cutoff) {
            var diff = data.related_pdbs.length - cutoff;
            similar_ids = data.related_pdbs.slice(0, cutoff).join(', ') + ' and ' + diff + ' more';
        } else {
            similar_ids = data.related_pdbs.join(', ');
        }

        $(div_id + '_similar').html(similar_ids);
        $(div_id + '_eq_class').attr('href', urls.equivalence_class + data.eq_class)
                               .html(data.eq_class);
    }

    my.load_structure_data = function(div_id, pdb_id)
    {
        my.get_pdb_info(pdb_id, div_id);
        my.get_similar_structures(pdb_id, div_id);
        $(div_id + '_fragments').children().remove();
        $(div_id).show();
    }

    return my;
}(jQuery));
