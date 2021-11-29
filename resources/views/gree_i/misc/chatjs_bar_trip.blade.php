<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
<body style="overflow: hidden">
<div id="container" style="height: 340px; width: 500px">
    {!! $finalityAmountMonth->container() !!}
</div>

{{-- ChartScript --}}
@if($finalityAmountMonth)
{!! $finalityAmountMonth->script() !!}
@endif

<img id="chartImage" width="500px" height="340px">
<script src="/js/plugins/chartjs/Chart.bundle.min.js"></script>

<form action="/misc/base64/" method="POST" id="genimg">
    <input type="hidden" name="base64" id="base64" value="">
</form>

<script>
function genImg() { 

    $("#container").hide();
    $('#chartImage').attr('src', window.<?= $finalityAmountMonth->id ?>.toBase64Image());
    $("#base64").val(window.<?= $finalityAmountMonth->id ?>.toBase64Image());
    console.log(window.<?= $finalityAmountMonth->id ?>.toBase64Image());

    $("#genimg").submit();
}

$(document).ready(function () {
    setTimeout(() => {
        genImg();
    }, 10);
});
</script>


</body>