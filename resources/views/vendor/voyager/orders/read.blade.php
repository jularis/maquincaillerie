@extends('voyager::master')

@section('page_title', 'Voir Commande')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> Commande {{ $dataTypeContent->order_number }}
        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.orders.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> Modifier
            </a>
        @endcan
        @can('browse', $dataTypeContent)
            <a href="{{ route('voyager.orders.index') }}" class="btn btn-warning">
                <i class="glyphicon glyphicon-list"></i> Retour à la liste
            </a>
        @endcan
    </h1>
@stop

@section('content')
<div class="page-content container-fluid">
<div class="row">
<div class="col-md-12">

    {{-- Champs standard Voyager --}}
    <div class="panel panel-bordered" style="padding-bottom:5px;">
        @foreach($dataType->readRows as $row)
        @php
            if ($dataTypeContent->{$row->field.'_read'}) {
                $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
            }
        @endphp
        <div class="panel-heading" style="border-bottom:0;">
            <h3 class="panel-title">{{ $row->getTranslatedAttribute('display_name') }}</h3>
        </div>
        <div class="panel-body" style="padding-top:0;">
            @if($row->type == 'select_dropdown' && property_exists($row->details, 'options') && !empty($row->details->options->{$dataTypeContent->{$row->field}}))
                {{ $row->details->options->{$dataTypeContent->{$row->field}} }}
            @elseif($row->type == 'date' || $row->type == 'timestamp')
                {{ $dataTypeContent->{$row->field} ? \Carbon\Carbon::parse($dataTypeContent->{$row->field})->format('d/m/Y à H:i') : '—' }}
            @elseif($row->type == 'rich_text_box')
                {!! $dataTypeContent->{$row->field} !!}
            @else
                <p>{{ $dataTypeContent->{$row->field} ?? '—' }}</p>
            @endif
        </div>
        @if(!$loop->last)<hr style="margin:0;">@endif
        @endforeach
    </div>

    {{-- Articles commandés --}}
    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="voyager-bag"></i> Articles commandés</h3>
        </div>
        <div class="panel-body" style="padding:0;">
            @php $items = is_array($dataTypeContent->items) ? $dataTypeContent->items : json_decode($dataTypeContent->items, true); @endphp
            @if(!empty($items))
            <table class="table table-bordered table-striped" style="margin:0;">
                <thead>
                    <tr>
                        <th style="width:50%">Produit</th>
                        <th class="text-center" style="width:15%">Quantité</th>
                        <th class="text-right" style="width:20%">Prix unitaire</th>
                        <th class="text-right" style="width:15%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td><strong>{{ $item['name'] }}</strong></td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ number_format($item['price'], 0, ',', ' ') }} F CFA</td>
                        <td class="text-right"><strong>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} F CFA</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Sous-total HT</strong></td>
                        <td class="text-right">{{ number_format($dataTypeContent->subtotal, 0, ',', ' ') }} F CFA</td>
                    </tr>
                    @if($dataTypeContent->tax > 0)
                    <tr>
                        <td colspan="3" class="text-right">TVA (18%)</td>
                        <td class="text-right">{{ number_format($dataTypeContent->tax, 0, ',', ' ') }} F CFA</td>
                    </tr>
                    @endif
                    <tr style="background:#f5f5f5;">
                        <td colspan="3" class="text-right"><strong>Total TTC</strong></td>
                        <td class="text-right"><strong style="font-size:16px;">{{ number_format($dataTypeContent->total, 0, ',', ' ') }} F CFA</strong></td>
                    </tr>
                </tfoot>
            </table>
            @else
                <p class="text-center text-muted" style="padding:20px;">Aucun article trouvé.</p>
            @endif
        </div>
    </div>

</div>
</div>
</div>

{{-- Modal suppression --}}
<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="voyager-trash"></i> Supprimer cette commande ?</h4>
            </div>
            <div class="modal-footer">
                <form action="{{ route('voyager.orders.index') }}" id="delete_form" method="POST">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right" value="Confirmer la suppression">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
@stop
