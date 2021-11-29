@extends('gree_i.layout')

@section('content')
    <h2 class="content-heading">Novo ticket <small>Para qual tipo de problema você precisa de suporte? </small></h2>
    <div class="row">
        <div class="col-md-12">
            <!-- Normal Form -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Informações</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="gree">Escolha a Gree</label>
                        <select name="gree" class="form-control" id="gree">
                            <option value=""></option>
                            <option value="1">Manaus</option>
                            <option value="2">Sao Paulo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="necessity">Qual a sua necessidade?</label>
                        <select name="necessity" class="form-control" id="necessity">
                            <option value=""></option>
                            <option value="1">Problema interno</option>
                            <option value="2">Sistema Gree</option>
                        </select>
                    </div>
                    <div class="form-group system" style="display:none">
                        <label for="system">Relacionado a qual módulo?</label>
                        <select name="system" class="form-control" id="system">
                            <option value=""></option>
                            <option value="1">Viagem</option>
                            <option value="2">Usuários</option>
                            <option value="3">Projetos</option>
                            <option value="4">TI</option>
                            <option value="5">Blog</option>
                            <option value="6">Sac</option>
                            <option value="7">Pedido de Compras</option>
                            <option value="8">Configurações</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Descreva seu problema</label>
                        <textarea class="form-control" id="description" name="description" rows="6" placeholder="..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="attach">Precisa anexar algo?</label>
                        <input type="file" class="form-control" id="attach" name="attach" />
                    </div>

                
            </div>

            

        </div>

        <button type="submit" class="btn btn-square btn-outline-secondary" style="width: 100%;">ABRIR TICKET</button>
    </div>

    

    <script>
    $(document).ready(function () {

        $("#mTI").addClass("open");
        $("#mTINew").addClass("active");
        
    });
    </script>
@endsection