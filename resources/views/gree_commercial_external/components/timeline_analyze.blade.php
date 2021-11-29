<!-- Right sidebar -->
<!-- ============================================================== -->
<!-- .right-sidebar -->
<div class="right-sidebar" style="overflow: auto;">
    <div class="slimscrollright">
        <div class="rpanel-title"> Histórico de Aprovações <span><i class="ti-close right-side-toggle"></i></span> </div>
        <div class="r-panel-body" style="padding: 0px 20px;">
            <div class="steamline m-t-40">
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Right sidebar -->
<!-- ============================================================== -->
<script type="text/javascript">
	var has_cancelled = false;
    var json_result = null;
    var list = '';
	
    function analyzeTimeline($id) {
        $('.steamline').html('');
        block();
        ajaxSend('{{$url}}'+$id, {}, 'GET', '5000').then(function(result){
            unblock();
            list = '';
			if (result.imds.length == 0 && result.dir_revision.length == 0 && result.dir_judicial.length == 0 && result.dir_commercial.length == 0 && result.dir_financy.length == 0) {
				list = buildItemCancelAnalyze(result.who_cancel, list);	
			}
            json_result = result;
			has_cancelled = false;
            json_result.imds.forEach(function ($imd) {
				if (!isCancelRequest($imd.when)) {
					list += '<div class="sl-item">';
					if ($imd.user.picture)
						list += '<div class="sl-left"> <img class="img-circle" alt="user" src="'+$imd.user.picture+'"> </div>';
					else
						list += '<div class="sl-left bg-secondary"> <i class="ti-user"></i> </div>';

					list += '<div class="sl-right">';
					list += '<div class="font-medium">'+$imd.user.short_name;
					if ($imd.analyze == 1)
						list += '<span class="label label-warning" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 2px;left: 5px;">Em análise</span></div>';
					else if ($imd.analyze == 2)
						list += '<span class="label label-success" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 2px;left: 5px;">Aprovado <span style="font-size: 9px;color: #ffeb5b;">'+$imd.when+'</span> </span></div>';
					else
						list += '<span class="label label-danger" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 2px;left: 5px;">Reprovado <span style="font-size: 9px;color: #ffeb5b;">'+$imd.when+'</span> </span></div>';

					list += '<div style="font-size:10px">'+$imd.office+'</div>';
					list += '<div class="desc">'+$imd.description+'</div>';
					list += '</div>';
					list += '</div>';
				}
            });
			
			list = buildItemAnalyze(json_result.dir_revision);
			list = buildItemAnalyze(json_result.dir_judicial);
			list = buildItemAnalyze(json_result.dir_commercial);
			//list = buildItemAnalyze(json_result.dir_financy);

            $('.steamline').html(list);
            $(".right-sidebar").slideDown(50);
            $(".right-sidebar").toggleClass("shw-rside");
        }).catch(function(err){
            unblock();
            $error(err.message)
        })
    }
	
	function buildItemAnalyze(elem) {
		if (typeof elem != 'undefined') {
			if (typeof elem.user != 'undefined') {
				if (!isCancelRequest(elem.when)) {
					list += '<div class="sl-item">';
					if (elem.user.picture)
						list += '<div class="sl-left"> <img class="img-circle" alt="user" src="'+elem.user.picture+'"> </div>';
					else
						list += '<div class="sl-left bg-secondary"> <i class="ti-user"></i> </div>';

					list += '<div class="sl-right">';
					list += '<div class="font-medium">'+elem.user.short_name;
					if (elem.analyze == 1)
						list += '<span class="label label-warning" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 2px;left: 5px; float: right;">Em análise</span></div>';
					else if (elem.analyze == 2)
						list += '<span class="label label-success" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 2px;left: 5px; float: right;">Aprovado <span style="font-size: 9px;color: #ffeb5b;">'+elem.when+'</span></span></div>';
					else
						list += '<span class="label label-danger" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 2px;left: 5px; float: right;">Reprovado <span style="font-size: 9px;color: #ffeb5b;">'+elem.when+'</span> </span></div>';

					list += '<div style="font-size:10px">'+elem.office+'</div>';
					list += '<div class="desc">'+elem.description+'</div>';
					list += '</div>';
					list += '</div>';
				}
			}
		}
		
		return list;
	}
	
	function buildItemCancelAnalyze(elem) {
        if (typeof elem != 'undefined') {
            if (typeof elem.user != 'undefined') {
                list += '<div class="sl-item">';
                if (elem.user.picture)
                    list += '<div class="sl-left"> <img class="img-circle" alt="user" src="'+elem.user.picture+'"> </div>';
                else
                    list += '<div class="sl-left bg-secondary"> <i class="ti-user"></i> </div>';

                list += '<div class="sl-right">';
                list += '<div class="font-medium">'+elem.user.short_name;
                list += '<span class="label label-danger" style="font-size: 10px !important;padding: 2px 10px !important;position: relative;bottom: 2px;left: 5px; float: right;">Cancelado <span style="font-size: 9px;color: #ffeb5b;">'+elem.when+'</span></span></div>';
                list += '<div class="desc">'+elem.description+'</div>';
                list += '</div>';
                list += '</div>';
            }
        }

        return list;

    }

    function isCancelRequest(d) {
        if (has_cancelled)
            return true;

        if (typeof json_result.who_cancel.user != 'undefined') {
            if (d == '') {
                has_cancelled = true;
                list = buildItemCancelAnalyze(json_result.who_cancel, list);
                return true;
            } else if (process(json_result.who_cancel.when) >= process(d)) {
                has_cancelled = true;
                list = buildItemCancelAnalyze(json_result.who_cancel, list);
                return true;
            }
        }
        return false;
    }

    function process(date){
        var parts = date.split("/");
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }
</script>
