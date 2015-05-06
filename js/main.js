var isVisible = false;

function LookUpPDBInfo() {

    var a = $(this);

    re = /[a-zA-Z0-9]{4}/;
    pdb = re.exec(a.text());

    var loc = window.location.protocol + '//' + window.location.hostname;
    $.post(loc + '/rna3dhub/rest/getPdbInfo', {
        pdb: pdb[0]
    }, function (data) {
        console.log(data);
        a.popover({
            content: data,
            title: pdb[0],
            delay: 1200,
            html: true,
            animation: true,
            placement: a.offset().top - $(window).scrollTop() < 500 ? 'bottom' : 'top'
        });
        a.popover('show');
        isVisible = true;
    });
}
