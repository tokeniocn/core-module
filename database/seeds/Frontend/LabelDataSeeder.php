<?php


namespace Modules\Core\Seeds\Frontend;


use Modules\Core\Models\ListData;

class LabelDataSeeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {
        $value = "";
        $key = "label";
        $labelList = [
            [
                "key" => "system.recharge.explain",
                "remark" => "充币说明",
            ],
            [
                "key" => "system.withdraw.explain",
                "remark" => "提币说明",
            ],
            [
                "key" => "system.auth.explain",
                "remark" => "提交实名认证说明",
            ],
            [
                "key" => "user.Invitation.title",
                "remark" => "邀请页面标题内容",
            ],
            [
                "key" => "system.internal.trade1",
                "remark" => "内部转账页面一",
            ],
            [
                "key" => "system.internal.trade2",
                "remark" => "内部转账页面二",
            ],
            [
                "key" => "system.share.poster",
                "remark" => "分享海报路径，多张json格式",
            ],
            [
                "key" => "system.start.poster",
                "remark" => "启动画面路径",
            ],
            [
                "key" => "system.share.remark",
                "remark" => "分享默认文字",
            ],

        ];
        foreach ($labelList as $label) {
            $label['key'] = $key;
            $labelList['value'] = $value;
            ListData::create($label);
        }

    }
}