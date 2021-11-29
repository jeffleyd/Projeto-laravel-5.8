<!-- datatable start -->
<div>

    <div class="table-responsive-md">
        <table class="table mb-0">
          <thead class="thead-dark">
            <tr>
              
              <th>#ID</th>
              <th>{{ __('lending_i.lt_2') }}</th>
              <th></th>
              
            </tr>
          </thead>
          <tbody id="ListItens">
            @if($lending_pendings->isNotEmpty())
                @foreach ($lending_pendings as $key)
                    <tr>
                        <td>{{$key->code}}</td>
                        <td>{{formatMoney($key->amount)}}</td>
                        <td>
                        <button type="button" data-id="{{$key->id}}" data-object="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-success ml-1 select_lending">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Selecionar</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center; font-size: larger; padding: 50px;">NÃO EXISTE EMPRESTIMOS PENDENTES</td>
                </tr>
            @endif
        
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation" class="mt-2">
        <ul class="pagination justify-content-end">
            <?= $lending_pendings->links('vendor.pagination.ajax',['html_render' => $html_render]); ?>
        </ul>
    </nav>

</div>
<!-- datatable ends -->