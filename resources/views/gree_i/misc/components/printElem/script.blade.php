<script src="/js/html2canvas.min.js"></script>
<script>
    var wdgt_oldWidth;
    /**
     * Informe o ID do elemento que será renderizado.
     * @param {string} elem
     */
    async function wdgt_printElem(elem) {
        wdgt_oldWidth = $('#'+elem).width();
        $('#'+elem).width(1000);
        window.scrollTo(0, 0); // <-- this fixes the issue
        block();
        html2canvas(document.getElementById(elem), {
            allowTaint: true,
            taintTest: false
        }).then(canvas => {
            var w = window.open("");
            w.document.body.appendChild(canvas);
            $('#'+elem).width(wdgt_oldWidth);
            unblock();
        });
    }
</script>
