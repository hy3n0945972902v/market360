<!-- BEGIN: main -->
<div class="page">
    <h2 class="title">{LANG.listusers}</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <colg roup>
            <col style="width: 60%" />
            <col style="width: 15%" />
            </colgroup>
            <thead>
                <tr class="bg-lavender">
                    <td>
                        <a href="{username}" class="black text-uppercase">{LANG.account}</a>
                    </td>
                    <td>
                        <a href="{gender}" class="black text-uppercase">{LANG.gender}</a>
                    </td>
                    <td>
                        <a href="{regdate}" class="black text-uppercase">{LANG.regdate}</a>
                    </td>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: list -->
                <tr>
                    <td>
                        <a href="{USER.link}">{USER.username} <!-- BEGIN: fullname -->&nbsp;({USER.full_name})<!-- END: fullname --></a>
                    </td>
                    <td>{USER.gender}</td>
                    <td>{USER.regdate}</td>
                </tr>
                <!-- END: list -->
            </tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="3">{GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
</div>
<!-- END: main -->