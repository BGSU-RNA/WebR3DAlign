<div id="wrap">
  <div class="container r3dalign-homepage">
    <div class="content">

    <div class="row well well-small small about"> <!-- row structure 1 -->

      <p>
        <strong>Specify two RNA 3D structures that you would like to align and hit submit.</strong>
        You will be redirected to an interstitial page that you can bookmark.
        Processing usually takes 3-10 minutes.
        You can leave your email address if you wish to be notified once the results are ready.
        Take a look at <a href="">help</a> and <a href="">tutorial</a>
        and feel free to <a href="">contact us</a> if you have questions.
      </p>

      <p>
        <strong>
        R3DAlign is integrated with the <a href="http://rna.bgsu.edu/rna3dhub/nrlist">Non-redundant Atlas</a>
        </strong>
        where all RNA 3D structures are organized into <strong><em>equivalence classes</em></strong>
        according to organism, sequence and 3D similarity, and structure quality considerations.
        Each class is <strong><em>represented</em></strong> by a single structure.
        When a PDB id is selected, its
        <span class="label label-info">Redundancy report</span>
        is dynamically loaded from the <a href="http://rna.bgsu.edu/rna3dhub">RNA 3D Hub</a>.
      </p>

    </div>

    <form enctype="multipart/form-data" action="<?php echo base_url();?>query/new" name="main" method="post">

    <!-- structure 1 -->
    <div class="row well well-small">

      <div class="row">
        <h4 class="span12">First Structure</h4>
      </div>

      <div class="row small">

        <!-- pdb selection -->
        <div class="span4">

          <input type="text" tabIndex="1" data-provide="typeahead" class="typeahead span2"
                 autocomplete="off" placeholder="Enter PDB id" id="pdb1" name="pdb1"
                 data-structure="1">
          <span class="help-inline"><em>or upload a file</em></span>

          <br>

          <input type="file" name="upload_pdb1" id="upload_pdb1" size="20" />

        </div> <!-- pdb selection -->

        <!-- fragment selection -->
        <div class="span8 mol1_fragments"></div>

      </div>

      <!-- structure 1 info -->
      <div class="small mol1"></div>

    </div> <!-- structure 1 -->

    <!-- structure 2 -->
    <div class="row well well-small">

      <div class="row">
        <h4 class="span12">Second Structure</h4>
      </div>

      <!-- structure 2 controls -->
      <div class="row small">

        <!-- pdb selection -->
        <div class="span4">

          <input type="text" tabIndex="2" data-provide="typeahead" class="typeahead span2"
                 autocomplete="off" placeholder="Enter PDB id" id="pdb2" name="pdb2"
                 data-structure="2">
          <span class="help-inline"><em>or upload a file</em></span>

          <br>

          <input type="file" name="upload_pdb2" id="upload_pdb2" size="20" />

        </div> <!-- pdb selection -->

        <!-- fragment selection -->
        <div class="span8 mol2_fragments"></div>

      </div> <!-- structure 2 controls -->

      <!-- structure 2 info -->
      <div class="small mol2"></div>

    </div> <!-- row structure 2 -->

   <!-- Advanced options-->
    <div class="advanced-options row">
    <div class="row well well-small iteration1 span12" id="iteration1">
      <fieldset>
        <div class="span3">
          <strong>Iteration 1</strong><br>

          <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy1" name="discrepancy1">
          <span class="help-inline">
            Discrepancy (d)
            <i class="icon-question-sign" data-original-title="Enter a number between 0 and 0.7"></i>
          </span>
          <br>

          <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods1" name="neighborhoods1">
          <span class="help-inline">
            Neighborhoods (p)
            <i class="icon-question-sign" data-original-title="Enter an integer between 2 and 10"></i>
          </span>
          <br>

          <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth1" name="bandwidth1">
          <span class="help-inline">
            Alignment bandwidth (&beta;)
            <i class="icon-question-sign" data-original-title="Enter a positive integer"></i>
          </span>
          <br>
        </div>

        <div class="span3">
          <strong>Final clique-finding method:</strong>
          <i class="icon-question-sign" data-original-title="Help text here"></i>
          <label class="radio">
            <input type="radio" name="clique_method1" value="greedy" id="clique_method_greedy1" checked>
            Greedy (Faster)
          </label>
          <label class="radio">
            <input type="radio" name="clique_method1" value="full" id="clique_method_full1">
            Branch and Bound (Exact)
          </label>
        </div>

        <div class="span3">
          <strong>Seed alignment:</strong>
          <i class="icon-question-sign" data-original-title="Help text here"></i>
          <label class="radio">
            <input type="radio" name="seed" id="seed_default" value="NWseed" checked>
              Internally produced alignment
          </label>
          <label class="radio">
            <input type="radio" name="seed" id="seed_upload_toggle" value="Manual">
            Upload seed alignment (fasta)
          </label>
          <input type="file" name="seed_upload_file" id="seed_upload_file" size="20" disabled/>
        </div>

        <div class="span2">
          <label class="checkbox">
            <input type="checkbox" id="toggle_iteration2"> Use this alignment as the seed for next iteration?
          </label>
        </div>

        <button class="btn btn-mini reset-advanced pull-right" data-iteration="1" data-original-title="Reset default values for this iteration">
          <i class="icon-refresh"></i>
        </button>
        <input type="hidden" id="iteration_enabled1" name="iteration_enabled1">

      </fieldset>
    </div>

    <div class="row well well-small iteration2 span12" id="iteration2">
      <fieldset>
      <div class="span3">
        <strong>Iteration 2</strong><br>
        <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy2" name="discrepancy2">
        <span class="help-inline">Discrepancy (d)</span>
        <br>

        <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods2" name="neighborhoods2">
        <span class="help-inline">Neighborhoods (p)</span>
        <br>

        <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth2" name="bandwidth2">
        <span class="help-inline">Alignment bandwidth (&beta;)</span>
      </div>

      <div class="span3">
        <strong>Final clique-finding method:</strong>
        <label class="radio">
          <input type="radio" name="clique_method2" value="greedy" id="clique_method_greedy2" checked>
          Greedy (Faster)
        </label>
        <label class="radio">
          <input type="radio" name="clique_method2" value="full" id="clique_method_full2">
          Branch and Bound (Exact)
        </label>
      </div>

      <div class="span3">
      </div>

      <div class="span2">
        <label class="checkbox">
          <input type="checkbox" id="toggle_iteration3"> Use this alignment as the seed for next iteration?
        </label>
      </div>

      <input type="hidden" id="iteration_enabled2" name="iteration_enabled2">
      <button class="btn btn-mini reset-advanced pull-right" data-iteration="2" data-original-title="Reset default values for this iteration">
        <i class="icon-refresh"></i>
      </button>

      </fieldset>
   </div>

   <div class="row well well-small iteration3 span12" id="iteration3">
     <fieldset>
       <div class="span3">
         <strong>Iteration 3</strong><br>
         <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy3" name="discrepancy3">
         <span class="help-inline">Discrepancy (d)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods3" name="neighborhoods3">
         <span class="help-inline">Neighborhoods (p)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth3" name="bandwidth3">
         <span class="help-inline">Alignment bandwidth (&beta;)</span>
        </div>

        <div class="span3">
          <strong>Final clique-finding method:</strong>
          <label class="radio">
            <input type="radio" name="clique_method3" value="greedy" id="clique_method_greedy3" checked>
            Greedy (Faster)
          </label>
          <label class="radio">
            <input type="radio" name="clique_method3" value="full" id="clique_method_full3">
            Branch and Bound (Exact)
          </label>
        </div>

        <input type="hidden" id="iteration_enabled3" name="iteration_enabled3">
        <button class="btn btn-mini reset-advanced pull-right" data-iteration="3" data-original-title="Reset default values for this iteration">
          <i class="icon-refresh"></i>
        </button>

      </fieldset>
    </div>

    </div> <!-- advanced options -->

    <div class="row well well-small form-inline"> <!-- form controls -->
      <button type="button" class="btn" id="toggle_advanced">Show advanced options</button>
      <button type="button" class="btn" id="reset">Reset</button>

      <div class="btn-group dropup">
        <a class="btn dropdown-toggle small" data-toggle="dropdown" href="#">
          Examples
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a id="rrna_16s">16S</a></li>
          <li><a id="rrna_5s_partial">5S rRNA (partial chains)</a></li>
          <li><a id="rrna_5s_complete">5S rRNA (complete chains)</a></li>
          <li><a id="rnase_p">RNase P</a></li>
        </ul>
      </div>

      <input type="email" placeholder="Email (optional)" id="email" name="email">
      <span class="alert alert-success small results"></span>
      <button type="submit" class="btn btn-primary pull-right span2 disabled" id="submit" disabled="disabled"><i class="icon-ok icon-white"></i> Submit</button>
    </div> <!-- row form controls -->


    </form>
    </div> <!-- content -->
  </div> <!-- container -->
</div> <!-- wrap -->


<script type="text/javascript" src="<?php echo base_url(); ?>js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/handlebars-1.0.rc.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/main.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/homepage.js"></script>

<script>
    Handlebars.registerHelper('pdbList', function() {
      var out = "";

      for(var i=0, l=this.related_pdbs.length; i<l; i++) {
        out = out + '<a class="' + this.popoverClass + '">' + this.related_pdbs[i] + "</a>";
        if ( i != l-1 ) {
            out += ', ';
        }
      }
      return new Handlebars.SafeString(out);
    });
</script>

<!-- Handlebars templates -->
<script id="chain-fragment" type="text/x-handlebars-template">
  <div class="form-inline fragment">

    <select name="{{chainSelectName}}" class="span4">
      {{#each rna_compounds}}
        <option value="{{chain}}">{{chain}}: {{compound}} ({{length}} nts)</option>
      {{/each}}
    </select>

    <div class="input-append input-prepend">
      <span class="add-on">
        <i class="icon-question-sign" data-html="true"
           data-original-title="Enter nucleotides to align.<br>
                                Ranges can be specified using a colon<br>
                                and can be separated by commas.<br>
                                Example: 2:20,62:69,110:119"></i>
      </span>
      <input class="input-medium" name="{{ntsInputName}}" type="text" placeholder="leave blank to use all">
      <button class="btn minus-fragment" type="button"><i class="icon-minus-sign"></i></button>
      <button class="btn plus-fragment" type="button"><i class="icon-plus-sign"></i></button>
    </div>

  </div>
</script>

<script id="representative-structure" type="text/x-handlebars-template">
  <div>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    represents
    {{numStructures}}
    other structures ({{pdbList}})
    together forming an
    <a href="{{url}}" target="_blank">equivalence class</a>.
  </div>
</script>

<script id="single-member" type="text/x-handlebars-template">
  <div>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    is the single member of an
    <a href="{{url}}" target="_blank">equivalence class</a>.
  </div>
</script>

<script id="the-only-other-member" type="text/x-handlebars-template">
  {{! 1A4D is represented by 1A51, which together form NR_all_39400.1 }}
  <div>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    is represented by
    <a class="{{popoverClass}}">{{representative}}</a>,
    which together form an
    <a href="{{url}}" target="_blank">equivalence class</a>.
  </div>
</script>

<script id="regular-member" type="text/x-handlebars-template">
  {{! special case 3KLV }}
  <div>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    is represented by
    <a class="{{popoverClass}}">{{representative}}</a>
    along with
    {{numStructures}} other
    structure{{#if manyStructures}}s{{/if}}
    ({{pdbList}}),
    which together form an
    <a href="{{url}}" target="_blank">equivalence class</a>.
  </div>
</script>

<script id="no-equivalence-class" type="text/x-handlebars-template">
  <div class="alert alert-error">
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    hasn't been included in the
    <a href="http://rna.bgsu.edu/rna3dhub/nrlist">Non-redundant Atlas</a>.
    Either it does not have any complete nucleotides,
    or it hasn't been processed yet.
  </div>
</script>


<script>

$(function() {
    $('form').submit(function(e) {
//         e.preventDefault();

        Validator.check_iterations();
        Validator.replace_empty_nucleotide_fields();

//         return false;

//         $(this).unbind('submit').submit();
    });
});


// $(function() {
//    $('form').submit(function(e) {
//       e.preventDefault();
//       $.ajaxFileUpload({
//          url         :'<?php echo base_url(); ?>/upload/upload_file/',
//          secureuri      :false,
//          fileElementId  :'upload1',
//          dataType    : 'json',
//          data        : {
//             'title'           : 'test'
//          },
//          success  : function (data, status)
//          {
//             if(data.status != 'error')
//             {
//                $('.results').html('Done');
//             }
//             alert(data.msg);
//          }
//       });
//       return false;
//    });
// });


$(function() {

    Events.bind_events();
    Examples.bind_events();

    $('.icon-question-sign').tooltip();
    $('.reset-advanced').tooltip();

    $('.typeahead').typeahead({
        source: function (query, process) {
            return $.get('http://rna.bgsu.edu/rna3dhub_dev/apiv1/get_all_rna_pdb_ids', { query: query }, function (data) {
                return process(data.pdb_ids);
            });
        }
    });

});

</script>