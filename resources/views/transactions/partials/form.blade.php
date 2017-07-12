@inject('fieldValues', 'App\Services\FormFieldOptionService')

@push('footer')
<script type="text/javascript">
    $(document).ready(function () {

        kendo.ui.DropDownList.prototype.options =
            $.extend(kendo.ui.DropDownList.prototype.options, {
                noDataTemplate: Lang.get('No Data found.'),
                optionLabel: Lang.get("Please Choose"),
            });

        $("#to_account").data("kendoDropDownList", new kendo.ui.DropDownList($("#to_account"), {
            filter: "startswith",
        }));

        $("#to_account").data("kendoDropDownList").enable(false);

        $("#category").data("kendoDropDownList", new kendo.ui.DropDownList($("#category"), {
            filter: "startswith",
            dataTextField: "name",
            dataValueField: "id",
            height: 300,
            dataSource: {
                serverFiltering: false,
                transport: {
                    read: "/api/v1/category/"
                },
                schema: {
                    data: "data"
                }
            }
        }));

        $("#subcategory").data("kendoDropDownList", new kendo.ui.DropDownList($("#subcategory"), {
            autoBind: false,
            cascadeFrom: "category",
            filter: "startswith",
            optionLabel: Lang.get("Please Choose"),
            dataTextField: "name",
            dataValueField: "id",
            height: 300,
            dataSource: {
                serverFiltering: true,
                transport: {
                    read: {
                        dataType: "json",
                        url: function () {
                            return "/api/v1/category/" + $("#category").data("kendoDropDownList").value() + "/subcategories"
                        }
                    }
                },
                schema: {
                    data: "data"
                }
            }
        }));

        $(".numeric-currency").each((index, elm) => {
            new kendo.ui.NumericTextBox($(elm), {
                format: "c",
                decimals: 2
            });
        });
    });
</script>
@endpush

@include('partials.form-errors')

<input type="hidden" name="id" value="{{old('id', $transaction ? $transaction->id : null)}}">

<div class="form-group label-static is-empty">
    <label for="transaction_date" class="control-label">@lang('Date')</label>
    <input type="date-local" name="transaction_date" placeholder="Von"
           value="{{old('transaction_date', $transaction ? $transaction->transaction_date : null)}}">
</div>

<div class="form-group label-static is-empty">
    <label for="transaction_status" class="control-label">@lang('Status')</label>
    <select name="transaction_status" class="common-dropdown-list">
        <option value="">@lang('Choose Status')</option>
        @foreach($fieldValues->getValues(App\Models\TransactionStatus::class) as $value)
            <option @if (old('transaction_status', $transaction ? $transaction->status_id : null) == $value->id) selected=""
                    @endif value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group label-static is-empty">
    <label for="transaction_type" class="control-label">@lang('Type')</label>
    <select name="transaction_type" class="common-dropdown-list">
        @foreach($fieldValues->getValues(App\Models\TransactionType::class) as $value)
            <option @if (old('transaction_type', $transaction ? $transaction->type_id: null) == $value->id) selected=""
                    @endif value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="account" class="control-label">@lang('Account')</label>
    <select name="account" class="common-dropdown-list">
        @foreach($fieldValues->getValues(App\Models\Account::class) as $value)
            <option @if (old('account',$transaction ? $transaction->account_id : null) == $value->id) selected=""
                    @endif value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="to_account" class="control-label">@lang('to Account')</label>
    <select id="to_account" name="to_account">
        <option value="">@lang('Choose Account')</option>
        @foreach($fieldValues->getValues(App\Models\Account::class) as $value)
            <option @if (old('to_account', $transaction ? $transaction->to_account_id : null) == $value->id) selected=""
                    @endif value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="payee" class="control-label">@lang('Payee')</label>
    <select name="payee" class="common-dropdown-list">
        @foreach($fieldValues->getValues(App\Models\Payee::class) as $value)
            <option @if (old('payee', $transaction ? $transaction->payee_id : null) == $value->id) selected=""
                    @endif value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group label-static is-empty">
    <label for="category" class="control-label">@lang('Category')</label>
    <input id="category" name="category" value="{{old('category', $transaction ? $transaction->category_id : null)}}">
</div>
<div class="form-group label-static is-empty">
    <label for="subcategory" class="control-label">@lang('Subcategory')</label>
    <input id="subcategory" name="subcategory"
           value="{{old('subcategory', $transaction ? $transaction->sub_category_id : null)}}">
</div>
<div class="form-group label-static is-empty">
    <label for="amount" class="control-label">@lang('Amount')</label>
    <input name="amount" type="number" title="currency"
           value="{{old('amount', $transaction ? $transaction->amount : null)}}"
           min="0"
           class="numeric-currency"/>
</div>

<div class="form-group label-static is-empty">
    <label for="notes" class="control-label">@lang('Notes')</label>
    <textarea name="notes" class="form-control" rows="5"
              placeholder="@lang('New transaction notes')">{{old('notes', $transaction ? $transaction->notes : null)}}</textarea>
</div>
<div class="form-group label-static is-empty">
    <label for="inputFile" class="control-label">@lang('Take a picture or upload attachments')</label>

    <input type="text" readonly="" class="form-control" placeholder="Browse...">
    <input type="file" multiple="" name="attachments[]">
    <span class="help-block">
        {{ini_get('upload_max_filesize')}}/{{ini_get('post_max_size')}}
    </span>
</div>