<div id="wrap">
  <div class="container r3dalign-homepage">
    <div class="content">

<noscript>
<div class="alert alert-error">
Please enable javascript in your browser settings because it is required for R3D Align
presubmission validation. If you continue using the site without javascript,
please make sure that all input data are correct.
</div>
</noscript>

<div id="myCarousel" class="carousel slide">
  <!-- Carousel items -->
  <div class="carousel-inner">
    <div class="active item hero-unit">
        <strong>R3D Align</strong> is a web application for
        <strong>locally optimized, nucleotide to nucleotide
        pairwise alignments of RNA 3D structures</strong>
        based on the method described by
        <a href="http://www.ncbi.nlm.nih.gov/pubmed/20929913" target="_blank">Rahrig et al., 2010</a>.
        The webserver is described in
        <a href="http://nar.oxfordjournals.org/content/41/W1/W15.full" target="_blank">Rahrig et al., 2013</a>.
        <br>
        Refer to <a href="<?=$this->config->item('home_url')?>/main/r3dalign-help" target="_blank">help</a>
        and don't hesitate to contact us if you have questions.
    </div>
    <div class="item hero-unit">
        <strong>Quickstart.</strong>
        Specify two RNA 3D structures that you would like to align.
        You can leave your email address if you wish to be notified once the results are ready.
        Click Submit.
        You will be redirected to an interstitial page that you can bookmark.
        Processing usually takes 3-10 minutes.
    </div>
    <div class="item hero-unit">
        <strong>
        <a href="<?=$this->config->item('home_url')?>/rna3dhub" target="_blank">RNA 3D Hub</a> integration.
        </strong>
        R3D Align is integrated with the
         <a href="<?=$this->config->item('home_url')?>/rna3dhub/nrlist" target="_blank">Representative sets</a>
        where all RNA 3D structures are organized into <strong><em>equivalence classes</em></strong>
        according to organism, sequence and 3D similarity, and structure quality considerations.
        Each class is <strong><em>represented</em></strong> by a single structure.
        When a PDB id is selected, its
        <span class="label label-info">Redundancy report</span>
        is dynamically retrieved.
    </div>
  </div>
  <!-- Carousel nav -->
  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>

    <form enctype="multipart/form-data" action="<?php echo base_url();?>query/new" name="main" method="post">

    <input type="hidden" id="isValid" value="0">

    <!-- structure 1 -->
    <div class="row well well-small">

      <div class="row">
        <h4 class="span12">First Structure</h4>
      </div>

      <div class="row small">
        <div class="span4">
          <input type="text" tabIndex="1" data-provide="typeahead" class="typeahead span2"
                 autocomplete="off" placeholder="Enter PDB id" id="pdb1" name="pdb1"
                 data-structure="1">
          <input type="text" name="upload_pdb1" id="upload_pdb1" placeholder="Leave blank"/>

        </div> <!-- pdb selection -->

        <div class="span8 mol1_fragments"></div> <!-- fragment selection -->
      </div>

      <div class="small mol1"></div> <!-- structure 1 info -->

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
          <input type="text" name="upload_pdb2" id="upload_pdb2" placeholder="Leave blank"/>

        </div> <!-- pdb selection -->

        <div class="span8 mol2_fragments"></div> <!-- fragment selection -->
      </div> <!-- structure 2 controls -->

      <div class="small mol2"></div> <!-- structure 2 info -->

    </div> <!-- row structure 2 -->

    <div class="progress active">
        <div class="bar" style="width: 0%;"></div>
    </div>

    <div id="message"></div>

   <!-- Advanced options-->
    <div class="advanced-options row well well-small">

        <div class="span3" id="iteration1">
          <strong>Iteration 1</strong>
          <br>
          <fieldset>
          <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy1"
                 name="discrepancy1" data-original-title="Enter a number between 0 and 1.0">
          <span class="help-inline">
            Discrepancy (d)
          </span>
          <br>

          <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods1"
                 name="neighborhoods1" data-original-title="Enter an integer between 1 and 10">
          <span class="help-inline">
            Neighborhoods (p)
          </span>
          <br>

          <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth1"
                 name="bandwidth1" data-original-title="Enter a positive integer">
          <span class="help-inline">
            Alignment bandwidth (&beta;)
          </span>
          <br>

          <label class="checkbox">
            <input type="checkbox" id="toggle_iteration2">Add iteration
          </label>
          <input type="hidden" id="iteration_enabled1" name="iteration_enabled1">
          </fieldset>
        </div>

        <div class="iteration2 span3" id="iteration2">
          <fieldset>
            <strong>Iteration 2</strong>
            <br>
            <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy2"
                   name="discrepancy2" data-original-title="Enter a number between 0 and 1.0">
            <span class="help-inline">Discrepancy</span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods2"
                   name="neighborhoods2" data-original-title="Enter an integer between 1 and 10">
            <span class="help-inline">Neighborhoods</span>
            <br>

            <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth2"
                   name="bandwidth2" data-original-title="Enter a positive integer">
            <span class="help-inline">Alignment bandwidth</span>
            <label class="checkbox">
              <input type="checkbox" id="toggle_iteration3">Add iteration
            </label>
            <input type="hidden" id="iteration_enabled2" name="iteration_enabled2">
          </fieldset>
       </div>

       <div class="iteration3 span3" id="iteration3">
         <fieldset>
             <strong>Iteration 3</strong><br>
             <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy3"
                    name="discrepancy3" data-original-title="Enter a number between 0 and 1.0">
             <span class="help-inline">Discrepancy</span>
             <br>

             <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods3"
                    name="neighborhoods3" data-original-title="Enter an integer between 1 and 10">
             <span class="help-inline">Neighborhoods</span>
             <br>

             <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth3"
                    name="bandwidth3" data-original-title="Enter a positive integer">
             <span class="help-inline">Alignment bandwidth</span>
             <input type="hidden" id="iteration_enabled3" name="iteration_enabled3">
          </fieldset>
       </div>

       <div class="span3">

         <div class="btn-group dropup suggest-parameters">
           <button class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
             Suggest parameters
             <span class="caret"></span>
           </button>
           <ul class="dropdown-menu">
             <li><a id="parameters_small">Small (&lt;50 nts)</a></li>
             <li><a id="parameters_medium">Medium (50-200 nts)</a></li>
             <li><a id="parameters_large">Large (&gt;200 nts)</a></li>
           </ul>
         </div>

         <br>

         <small>
            <i class="icon-question-sign"></i> Choose among 3 sets of
            parameters that usually work well for
            large, medium, and small structures.
            <a href="<?=$this->config->item('home_url')?>/main/r3dalign-help#advanced-parameters" target="_blank">More</a>
         </small>
       </div>

    </div> <!-- advanced options -->

    <div class="row well well-small form-inline"> <!-- form controls -->

      <button class="btn" id="toggle_advanced">Advanced parameters</button>

      <div class="btn-group dropup">
        <a class="btn dropdown-toggle small" data-toggle="dropdown" href="#">
          Examples
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a id="rrna_16s">16S rRNA</a></li>
          <li><a id="rrna_23s">23S rRNA</a></li>
          <li><a id="rrna_5s_partial">5S rRNA (partial chains)</a></li>
          <li><a id="rrna_5s_complete">5S rRNA (complete chains)</a></li>
        </ul>
      </div>

      <input type="email" placeholder="Email (optional)" id="email" name="email">
      <div class="btn-group pull-right">
        <button class="btn" id="reset"><i class="icon-refresh"></i> Reset</button>
        <button type="submit" class="btn btn-primary" id="submit_btn"><i class="icon-ok icon-white"></i> Submit</button>
      </div>
    </div> <!-- row form controls -->

    </form>
    </div> <!-- content -->
  </div> <!-- container -->
</div> <!-- wrap -->


<script type="text/javascript" src="<?php echo base_url(); ?>js/handlebars-1.0.rc.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/main.js?v=1.1"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/homepage.js?v=1.1"></script>


<!-- Handlebars templates -->
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

<script id="upload-input-fragment" type="text/x-handlebars-template">
  <div class="form-inline fragment">

    <div class="input-prepend">
      <span class="add-on">
        <i class="icon-question-sign" data-html="true"
           data-original-title="Select a chain to align.<br>
                                Nucleotides and chains are not validated<br>
                                in uploaded files.<br>
                                Example: C"></i>
      </span>

    <input name="{{chainSelectName}}" class="span2" type="text" autocomplete="off" placeholder="select a chain">
    </div>

    <div class="input-append input-prepend">
      <span class="add-on">
        <i class="icon-question-sign" data-html="true"
           data-original-title="Enter nucleotides to align.<br>
                                Ranges can be specified using a colon<br>
                                and can be separated by commas.<br>
                                Example: 2:20,62:69,110:119"></i>
      </span>
      <input class="input-medium nt-validate" autocomplete="off" name="{{ntsInputName}}" type="text" placeholder="leave blank to use all">
      <button class="btn minus-fragment" type="button"><i class="icon-minus-sign"></i></button>
      <button class="btn plus-fragment" type="button"><i class="icon-plus-sign"></i></button>
    </div>

  </div>
</script>

<script id="chain-fragment" type="text/x-handlebars-template">
  <div class="form-inline fragment">

    <select name="{{chainSelectName}}" class="span4">
      {{#each rna_compounds}}
        <option value="{{chain_name}}">{{chain_name}}: {{compound}} ({{length}} nts)</option>
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
      <input class="input-medium nt-validate" autocomplete="off" name="{{ntsInputName}}" type="text" placeholder="leave blank to use all">
      <button class="btn minus-fragment" type="button"><i class="icon-minus-sign"></i></button>
      <button class="btn plus-fragment" type="button"><i class="icon-plus-sign"></i></button>
    </div>

  </div>
</script>

<script id="representative-structure" type="text/x-handlebars-template">
  <div>
    <small>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    represents
    {{numStructures}}
    other structures ({{pdbList}})
    together forming an
    <a href="{{url}}" target="_blank">equivalence class</a>.
    </small>
  </div>
</script>

<script id="single-member" type="text/x-handlebars-template">
  <div>
    <small>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    is the single member of an
    <a href="{{url}}" target="_blank">equivalence class</a>.
    </small>
  </div>
</script>

<script id="the-only-other-member" type="text/x-handlebars-template">
  {{! 1A4D is represented by 1A51, which together form NR_all_39400.1 }}
  <div>
    <small>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    is represented by
    <a class="{{popoverClass}}">{{representative}}</a>,
    which together form an
    <a href="{{url}}" target="_blank">equivalence class</a>.
    </small>
  </div>
</script>

<script id="regular-member" type="text/x-handlebars-template">
  {{! special case 3KLV }}
  <div>
    <small>
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    belongs to the <a href="{{url}}" target="_blank">equivalence class</a>
    represented by
    <a class="{{popoverClass}}">{{representative}}</a>
    and consisting of
    {{numStructures}} other
    structure{{#if manyStructures}}s{{/if}}
    ({{pdbList}}).
    </small>
  </div>
</script>

<script id="no-equivalence-class" type="text/x-handlebars-template">
  <div class="alert alert-error">
    <span class="label label-info">Redundancy report</span>
    <a class="{{popoverClass}}">{{pdbId}}</a>
    hasn't been included in the
    <a href="<?=$this->config->item('home_url')?>/rna3dhub/nrlist">Representative sets</a>.
    Either it does not have any complete nucleotides,
    or it hasn't been processed yet.
  </div>
</script>


<script>

$(function() {

    Events.bindEvents();
    Examples.bindEvents();
    Validator.validate();

    $('input').tooltip({
        trigger: 'focus'
    });

    $('.carousel').carousel('pause');

    $('.typeahead').typeahead({
        source: function (query, process) {
            return $.get('<?=$this->config->item('home_url')?>/rna3dhub/apiv1/get_all_rna_pdb_ids', { query: query }, function (data) {
                return process(data.pdb_ids);
            });
        }
    });

    // prevent form submission when Enter is pressed in input fields
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    // to dismiss popovers when clicking outside
    $('html').click(function (e) {
        if (isVisible) {
            $('.pdb_info').popover('destroy');
            isVisible = false;
        }
    });

});

</script>
