function LookUpPDBInfo()
{
    var a = $(this);
	re = /[a-zA-Z0-9]{4}/;
	pdb = re.exec(a.text());

    $.post('http://rna.bgsu.edu/rna3dhub/rest/getPdbInfo', { pdb: pdb[0] }, function(data) {
        $('.popover').prev().popover('destroy').on('click', LookUpPDBInfo);
        a.unbind('click', LookUpPDBInfo);
        a.popover({
          content: data,
          title: pdb[0],
          delay: 1200,
          html: true,
          animation: true,
          placement: a.offset().top - $(window).scrollTop() < 500 ? 'bottom' : 'top'
        });
        a.popover('show');
    });
}
