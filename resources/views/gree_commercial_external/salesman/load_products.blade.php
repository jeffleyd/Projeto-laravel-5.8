


    {{-- <div class="row" style="background: #edf1f5;"> --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($months->count() > 0)
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach ($months as $indx => $key)
                            <li class="nav-item"> <a class="nav-link @if (date('m-Y', strtotime($key->date)) == date('m-Y')) active show @endif " data-toggle="tab" href="#month-tab-{{$indx}}" role="tab" aria-selected="true">
                                <span class="hidden-sm-up">
                                    {{date('m/Y', strtotime($key->date))}}
                                </span>
                                    <span class="hidden-xs-down">{{$month[date('n', strtotime($key->date))]}} ({{date('Y', strtotime($key->date))}})</span></a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content tabcontent-border">
                        @foreach ($months as $indx => $key)
                            <div class="tab-pane @if (date('m-Y', strtotime($key->date)) == date('m-Y')) active show @endif " id="month-tab-{{$indx}}" role="tabpanel">
                                <div class="p-20">
                                    <div class="row">
                                    @foreach ($products as $item)
                                        <div class="col-sm-6">
                                            <div class="col-sm-12">
                                                <fieldset style="text-align: center;padding: 5px;margin: 15px;background: #f6ebd5;">
                                                    <b>{{$item->name}}</b>
                                                </fieldset>
                                            </div>
                                            <div class="col-sm-12">
                                                <fieldset>
                                                    <div class="row">
                                                        <div class="col-xl-6 col-sm-12 mb-sm-2" style="margin-bottom: 20px;">
                                                            <div class="list-group">
                                                                @foreach ($item->setProductOnGroup as $set)
                                                                    @if ($set->is_qf == 0 and $set->is_visible)
                                                                        <div class="list-group-item list-group-item-info flex-column align-items-start">
                                                                            <div class="d-flex w-100 justify-content-between">
                                                                                <small><span style="font-weight: bold;">{{$set->resume}}</span></small>
                                                                                <small data-price="{{$set->price_base}}" data-has-type-client="{{$set->has_type_client}}" data-adjust="{{implode(',', $set->condition_in_month[date('Y-n-01', strtotime($key->date))]['factors'])}}" data-date="{{date('Y-n', strtotime($key->date))}}" class="price_fr">{{formatMoney($set->price_base)}}
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-12" style="margin-bottom: 20px;">
                                                            <div class="list-group">
                                                                @foreach ($item->setProductOnGroup as $set)
                                                                    @if ($set->is_qf == 1 and $set->is_visible)
                                                                        <div class="list-group-item list-group-item active flex-column align-items-start">
                                                                            <div class="d-flex w-100 justify-content-between">
                                                                                <small><span style="font-weight: bold;" class="text-white">{{$set->resume}}</span></small>
                                                                                <small data-price="{{$set->price_base}}" data-has-type-client="{{$set->has_type_client}}" data-adjust="{{implode(',', $set->condition_in_month[date('Y-n-01', strtotime($key->date))]['factors'])}}" data-date="{{date('Y-n', strtotime($key->date))}}" class="price_qf">{{formatMoney($set->price_base)}}
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

    {{-- </div> --}}

    {{-- <div>
        {{$products->toJSON(JSON_PRETTY_PRINT)}}
    </div> --}}
