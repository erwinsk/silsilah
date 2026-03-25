@extends('layouts.user-profile-wide')

@section('subtitle', trans('app.family_chart'))

@section('user-content')
<div class="panel panel-default table-responsive">
    <table class="table table-bordered table-striped">
        <tbody>
            {{-- Kakek & Nenek --}}
            <tr>
                <th style="width: 15%">{{ trans('user.grand_father') }} & {{ trans('user.grand_mother') }}</th>
                <td class="text-center" colspan="1">
                    {{ $fatherGrandpa ? $fatherGrandpa->profileLink('chart') : '?' }}
                </td>
                <td class="text-center" colspan="1">
                    {{ $fatherGrandma ? $fatherGrandma->profileLink('chart') : '?' }}
                </td>
                <td class="text-center" colspan="1">
                    {{ $motherGrandpa ? $motherGrandpa->profileLink('chart') : '?' }}
                </td>
                <td class="text-center" colspan="1">
                    {{ $motherGrandma ? $motherGrandma->profileLink('chart') : '?' }}
                </td>
            </tr>
            {{-- Ayah & Ibu --}}
            <tr>
                <th>{{ trans('user.father') }} & {{ trans('user.mother') }}</th>
                <td class="text-center" colspan="2">
                    {{ $father ? $father->profileLink('chart') : '?' }}
                </td>
                <td class="text-center" colspan="2">
                    {{ $mother ? $mother->profileLink('chart') : '?' }}
                </td>
            </tr>
            {{-- User Utama --}}
            <tr>
                <th>&nbsp;</th>
                <td class="text-center" colspan="4">
                    <span class="lead"><strong>{{ $user->profileLink('chart') }} ({{ $user->gender }})</strong></span>
                </td>
            </tr>
            {{-- Pasangan (Spouse) --}}
            <tr>
                <th>{{ trans('user.spouse') }}</th>
                <td class="text-center" colspan="4">
                    @if ($user->husbands->isNotEmpty())
                    @foreach($user->husbands as $husband)
                    <div class="lead"><strong>{{ $husband->profileLink('chart') }} ({{ $husband->gender }})</strong></div>
                    @endforeach
                    @endif

                    @if ($user->wifes->isNotEmpty())
                    @foreach($user->wifes as $wife)
                    <div class="lead"><strong>{{ $wife->profileLink('chart') }} ({{ $wife->gender }})</strong></div>
                    @endforeach
                    @endif

                    @if ($user->husbands->isEmpty() && $user->wifes->isEmpty())
                    -
                    @endif
                </td>
            </tr>
            {{-- Anak & Cucu --}}
            <tr>
                <th>{{ trans('user.childs') }} & {{ trans('user.grand_childs') }}</th>
                <td colspan="4">
                    @php $no = 0; @endphp
                    @foreach($childs->chunk(4) as $chunkedChild)
                    <div class="row">
                        @foreach($chunkedChild as $child)
                        <div class="col-md-3">
                            <h4><strong>{{ ++$no }}. {{ $child->profileLink('chart') }} ({{ $child->gender }})</strong></h4>
                            <ul style="padding-left: 20px">
                                @foreach($child->childs as $grand)
                                <li>{{ $grand->profileLink('chart') }} ({{ $grand->gender }})</li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                    @if (! $loop->last)
                    <hr style="margin: 10px 0">
                    @endif
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
</div>

<h4 class="page-header">
    {{ trans('user.siblings') }}, {{ trans('user.nieces') }}, & {{ trans('user.grand_childs') }}
</h4>
@foreach ($siblings->chunk(3) as $chunkedSiblings)
<div class="row">
    @foreach ($chunkedSiblings as $sibling)
    <div class="col-sm-4">
        @include('users.partials.chart-sibling')
    </div>
    @endforeach
</div>
@endforeach
@endsection