<!-- BEGIN: main -->
<div class="clearfix">
    <h2 class="title pull-left">{LANG.sysexchange}</h2>
    <div class="pull-right wallet-button">
        <a class="btn btn-info" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=money">{LANG.money}</a> <a class="btn btn-info" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=historyexchange">{LANG.historyexchange}</a>
    </div>
</div>
<div class="table-responsive table_wallet content-body">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center" style="width: 120px;">{LANG.transaction_code}</th>
                <th class="text-right" style="width: 130px;">{LANG.moneytransaction}</th>
                <th class="text-right" style="width: 130px;">{LANG.mymoneychange}</th>
                <th class="text-right" style="width: 140px;">{LANG.datetransaction}</th>
                <th class="text-left" style="width: 130px;">{LANG.transition_status}</th>
                <th class="text-left">{LANG.infotransaction}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">{ROW.transaction_code}</td>
                <td class="text-right"><strong class="text-danger">{ROW.status}{ROW.money_net} {ROW.money_unit}</strong></td>
                <td class="text-right"><strong class="text-danger">{ROW.status}{ROW.money_total} {ROW.money_unit}</strong></td>
                <td class="text-right">{ROW.created_time}</td>
                <td class="text-left">{ROW.transaction_status}</td>
                <td class="text-left">{ROW.transaction_info}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- BEGIN: generate_page -->
<div class="text-right">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->