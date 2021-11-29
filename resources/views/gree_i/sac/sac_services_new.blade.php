@extends('gree_i.layout')

@section('content')
    <h2 class="content-heading">Novo atendimento <small>Especifique todas as informações corretamente</small></h2>
    <div class="row">
        <div class="col-md-12">
            <!-- Normal Form -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Informações pessoais</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="identity">CPF</label>
                        <input type="text" class="form-control" id="identity" name="identity" placeholder="...">
                    </div>
                    <div class="form-group">
                        <label for="name">Nome completo</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="...">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Telefone</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="address">Endereço</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="number">Número</label>
                                <input type="text" class="form-control" id="number" name="number" placeholder="...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="complement">Complemento</label>
                        <input type="text" class="form-control" id="complement" name="complement" placeholder="...">
                    </div>
                    <div class="form-group">
                        <label for="origin">Origem</label>
                        <select name="origin" class="form-control" id="origin">
                            <option value=""></option>
                            <option value="1">Telefone</option>
                            <option value="3">E-mail</option>
                            <option value="4">Reclame aqui</option>
                            <option value="5">Facebook</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shop">Loja</label>
                        <select name="shop" class="form-control" id="shop">
                            <option value=""></option>
                            <option value="1">Magalu</option>
                            <option value="3">Ricardo eletro</option>
                            <option value="4">Bemol</option>
                            <option value="5">Magazine luiza</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- END Normal Form -->

        </div>
        <div class="col-md-12">
            <!-- Normal Form -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Dados do aparelho</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="serie">Número de série</label>
                        <input type="text" class="form-control" id="serie" name="serie" placeholder="...">
                    </div>
                    <div class="form-group">
                        <label for="model">Modelo</label>
                        <input type="text" class="form-control" id="model" name="model" placeholder="...">
                    </div>
                    <div class="form-group">
                        <label for="volt">Selecione Voltagem</label>
                        <select name="volt" id="volt" class="form-control">
                            <option value=""></option>
                            <option value="110v">110v</option>
                            <option value="220v">220v</option>
                            <option value="380v">380v</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="datebuy">Data da compra</label>
                        <input type="text" class="form-control" id="datebuy" name="datebuy" placeholder="...">
                    </div>
                </div>
            </div>
            <!-- END Normal Form -->

                <button type="submit" class="btn btn-square btn-outline-secondary" style="width: 100%;">ABRIR ATENDIMENTO</button>

        </div>
    </div>

    <script>
    $(document).ready(function () {

        $("#mSac").addClass("open");
        $("#mSacServices").addClass("open");
        $("#mSacServicesNew").addClass("active");
        
    });
    </script>
@endsection