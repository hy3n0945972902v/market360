<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" id="modConf">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col class="w300" />
            <tbody>
                <tr>
                    <td>
                        <strong>{LANG.homeclips}</strong>
                    </td>
                    <td>
                        <select style="width: 500px" class="form-control" name="idhomeclips" id="idhomeclips">
                            <option value="0">-----</option>
                            <!-- BEGIN: idhomeclips -->
                            <option value="{VHOME.id}"{VHOME.select}>{VHOME.title}</option>
                            <!-- END: idhomeclips -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{LANG.NumberOfLinks}</strong>
                    </td>
                    <td>
                        <select class="form-control w200" name="otherClipsNum" id="otherClipsNum">
                            <!-- BEGIN: otherClipsNum -->
                            <option value="{NUMS.value}"{NUMS.select}>{NUMS.value}</option>
                            <!-- END: otherClipsNum -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{LANG.playerAutostart}</strong>
                    </td>
                    <td>
                        <input type="checkbox" value="1" name="playerAutostart" id="playerAutostart" {CONFIGMODULE.playerAutostart_checked} />
                    </td>
                </tr>
                <tr>
                    <th>{LANG.per_title}</th>
                    <td>
                        <input type="text" value="{CLEAN_TITLE_VIDEO}" name="clean_title_video" class="form-control w100" />
                </tr>
                <!-- BEGIN: hidden -->
                <tr>
                    <td>
                        <strong>{LANG.playerSkin}</strong>
                    </td>
                    <td>
                        <select class="form-control w200" name="playerSkin" id="playerSkin">
                            <option value="">{LANG.noSkin}</option>
                            <!-- BEGIN: playerSkin -->
                            <option value="{SKIN.value}"{SKIN.select}>{SKIN.value}</option>
                            <!-- END: playerSkin -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{LANG.embedMaxWidth}</strong>
                    </td>
                    <td>
                        <input class="form-control w200" type="text" name="playerMaxWidth" id="playerMaxWidth" value="{CONFIGMODULE.playerMaxWidth}" />
                    </td>
                </tr>
                <!-- END: hidden -->
            </tbody>
        </table>
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <em class="fa fa-file-text-o">&nbsp;</em>{LANG.config_get_video}
            </caption>
            <col class="w300" />
            <tbody>
                <tr>
                    <th>{LANG.config_api_key}</th>
                    <td>
                        <input type="text" value="{CONFIGMODULE.api_key}" name="api_key" class="form-control w500" />
                    </td>
                </tr>
                <tr>
                    <th>{LANG.config_channel_id}</th>
                    <td>
                        <input type="text" value="{CONFIGMODULE.channel_id}" name="channel_id" class="form-control w500" />
                    </td>
                </tr>
                <tr>
                    <th>{LANG.config_maxResults_playlist}</th>
                    <td>
                        <input type="text" value="{CONFIGMODULE.maxResults_playlist}" name="maxResults_playlist" class="form-control w500" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="width: 200px; margin: 10px auto; text-align: center;">
        <input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}" style="width: 100px;" />
    </div>
</form>
<!-- END: main -->