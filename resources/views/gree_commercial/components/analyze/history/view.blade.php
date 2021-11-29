<style>
    .right-sidebar {
        position: fixed;
        right: -340px;
        width: 340px;
        /*display: none;*/
        z-index: 1100;
        background: #fff;
        top: 0;
        padding-bottom: 20px;
        height: 100%;
        box-shadow: 5px 1px 40px rgb(0 0 0 / 10%);
        transition: all .3s ease;
    }
    .right-sidebar .rpanel-title {
        display: block;
        padding: 22px 20px;
        color: #fff;
        text-transform: uppercase;
        font-size: 15px;
        background: #3071a9;
    }
    .steamline .sl-left {
        float: left;
        margin-left: -5px;
        z-index: 1;
        width: 40px;
        line-height: 40px;
        text-align: center;
        height: 40px;
        border-radius: 100%;
        color: #fff;
        margin-right: 15px;
    }
    .steamline .sl-right {
        padding-left: 50px;
    }
    .steamline .sl-item {
        border-bottom: 1px solid #e9ecef;
        margin: 20px 0;
    }
    .right-side-toggle {
        cursor: pointer;
        color: white;
        position: relative;
        left: 52px;
        padding: 4px;
        bottom: 1px;
        font-size: 17px;
    }
    .profiletimeline .sl-left img, .steamline .sl-left img {
        max-width: 40px;
    }
    .img-circle {
        border-radius: 100%;
    }
</style>

<!-- Right sidebar -->
<!-- ============================================================== -->
<!-- .right-sidebar -->
<div class="right-sidebar" style="overflow: auto;">
    <div class="slimscrollright">
        <div class="rpanel-title"> Histórico de Aprovações <span><i class="fa fa-times right-side-toggle" style="cursor: pointer; color:white"></i></span> 
		</div>
        <div class="r-panel-body" style="padding: 0px 20px;">
			<fieldset class="form-group mb-0 select-version">
                <select class="form-control" id="rtd_version">
                </select>
                <p style="text-align: left">
                    <small>Escolha a versão para visualizar a situação</small>
                </p>
            </fieldset>
            <div class="steamline m-t-20" style="margin-top: 20px;"></div>
        </div>
    </div>
</div>
