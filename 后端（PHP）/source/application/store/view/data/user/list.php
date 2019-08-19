<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <link rel="stylesheet" href="assets/common/css/amazeui.min.css"/>
    <link rel="stylesheet" href="assets/store/css/app.css?v=<?= $version ?>"/>
    <script src="assets/common/js/jquery.min.js"></script>
    <title>用户列表</title>
</head>
<body class="select-data">
<!-- 工具栏 -->
<div class="page_toolbar am-margin-bottom-xs am-cf">
    <form class="toolbar-form" action="">
        <input type="hidden" name="s" value="/<?= $request->pathinfo() ?>">
        <div class="am-u-sm-12">
            <div class="am fr">
                <div class="am-form-group am-fl">
                    <?php $grade = $request->get('grade'); ?>
                    <select name="grade"
                            data-am-selected="{btnSize: 'sm', placeholder: '请选择会员等级'}">
                        <option value=""></option>
                        <?php foreach ($gradeList as $item): ?>
                            <option value="<?= $item['grade_id'] ?>"
                                <?= $grade == $item['grade_id'] ? 'selected' : '' ?>><?= $item['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="am-form-group am-fl">
                    <?php $gender = $request->get('gender'); ?>
                    <select name="gender"
                            data-am-selected="{btnSize: 'sm', placeholder: '请选择性别'}">
                        <option value=""></option>
                        <option value="-1"
                            <?= $gender === '-1' ? 'selected' : '' ?>>全部
                        </option>
                        <option value="1"
                            <?= $gender === '1' ? 'selected' : '' ?>>男
                        </option>
                        <option value="2"
                            <?= $gender === '2' ? 'selected' : '' ?>>女
                        </option>
                        <option value="0"
                            <?= $gender === '0' ? 'selected' : '' ?>>未知
                        </option>
                    </select>
                </div>
                <div class="am-form-group am-fl">
                    <div class="am-input-group am-input-group-sm tpl-form-border-form">
                        <input type="text" class="am-form-field" name="nickName"
                               placeholder="请输入微信昵称"
                               value="<?= $request->get('nickName') ?>">
                        <div class="am-input-group-btn">
                            <button class="am-btn am-btn-default am-icon-search"
                                    type="submit"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="am-scrollable-horizontal am-u-sm-12">
    <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black am-text-nowrap">
        <thead>
        <tr>
            <th>
                <label class="am-checkbox">
                    <input data-am-ucheck data-check="all" type="checkbox">
                </label>
            </th>
            <th>微信头像</th>
            <th>微信昵称</th>
            <th>用户余额</th>
            <th>会员等级</th>
            <th>累积消费金额</th>
            <th>性别</th>
            <!--            <th>国家</th>-->
            <!--            <th>省份</th>-->
            <!--            <th>城市</th>-->
            <th>注册时间</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
            <tr>
                <td class="am-text-middle">
                    <label class="am-checkbox">
                        <input data-am-ucheck data-check="item" data-params='<?= json_encode([
                            'user_id' => (string)$item['user_id'],
                            'nickName' => $item['nickName'],
                            'avatarUrl' => $item['avatarUrl'],
                        ], JSON_UNESCAPED_SLASHES) ?>' type="checkbox">
                    </label>
                </td>
                <td class="am-text-middle">
                    <a href="<?= $item['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                        <img src="<?= $item['avatarUrl'] ?>" width="72" height="72" alt="">
                    </a>
                </td>
                <td class="am-text-middle"><?= $item['nickName'] ?></td>
                <td class="am-text-middle"><?= $item['balance'] ?></td>
                <td class="am-text-middle">
                    <?= !empty($item['grade']) ? $item['grade']['name'] : '--' ?>
                </td>
                <td class="am-text-middle"><?= $item['expend_money'] ?></td>
                <!--
                <td class="am-text-middle"><?= $item['gender'] ?></td>
                <td class="am-text-middle"><?= $item['country'] ?: '--' ?></td>
                <td class="am-text-middle"><?= $item['province'] ?: '--' ?></td>
                -->
                <td class="am-text-middle"><?= $item['city'] ?: '--' ?></td>
                <td class="am-text-middle"><?= $item['create_time'] ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="8" class="am-text-center">暂无记录</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="am-u-lg-12 am-cf">
    <div class="am-fr"><?= $list->render() ?> </div>
    <div class="am-fr pagination-total am-margin-right">
        <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
    </div>
</div>

<script src="assets/common/js/amazeui.min.js"></script>
<script>

    /**
     * 获取已选择的数据
     * @returns {Array}
     */
    function getSelectedData() {
        var data = [];
        $('input[data-check=item]:checked').each(function () {
            data.push($(this).data('params'));
        });
        return data;
    }

    $(function () {

        // 全选框元素
        var $checkAll = $('input[data-check=all]')
            , $checkItem = $('input[data-check=item]')
            , itemCount = $checkItem.length;

        // 复选框: 全选和反选
        $checkAll.change(function () {
            $checkItem.prop('checked', this.checked);
        });

        // 复选框: 子元素
        $checkItem.change(function () {
            if (!this.checked) {
                $checkAll.prop('checked', false);
            } else {
                var checkedItemNum = $checkItem.filter(':checked').length;
                checkedItemNum === itemCount && $checkAll.prop('checked', true);
            }
        });

    });
</script>
</body>
</html>
