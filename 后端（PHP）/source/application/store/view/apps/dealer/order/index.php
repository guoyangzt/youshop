<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">分销订单</div>
                </div>
                <div class="widget-body am-fr">
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <form class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/<?= $request->pathinfo() ?>">
                            <input type="hidden" name="user_id" value="<?= $request->get('user_id') ?>">
                            <div class="am-u-sm-12 am-u-md-9 am-u-sm-push-3">
                                <div class="am fr">
                                    <div class="am-form-group am-fl">
                                        <select name="is_settled"
                                                data-am-selected="{btnSize: 'sm', placeholder: '是否结算佣金11'}">
                                            <option value=""></option>
                                            <option value="-1" <?= $request->get('is_settled') == '-1' ? 'selected' : '' ?>>
                                                全部
                                            </option>
                                            <option value="0" <?= $request->get('is_settled') === '0' ? 'selected' : '' ?>>
                                                未结算
                                            </option>
                                            <option value="1" <?= $request->get('is_settled') == '1' ? 'selected' : '' ?>>
                                                已结算
                                            </option>
                                        </select>
                                    </div>
                                    <div class="am-form-group am-fl" style="width: 80px;">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form">
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
                    <div class="order-list am-scrollable-horizontal am-u-sm-12 am-margin-top-xs">
                        <table width="100%" class="am-table am-table-centered
                        am-text-nowrap am-margin-bottom-xs">
                            <thead>
                            <tr>
                                <th width="30%" class="goods-detail">商品信息</th>
                                <th width="10%">单价/数量</th>
                                <th width="15%">实付款</th>
                                <th>买家</th>
                                <th>交易状态</th>
                                <th>佣金结算</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list->toArray()['data'] as $order): ?>
                                <tr class="order-empty">
                                    <td colspan="6"></td>
                                </tr>
                                <tr>
                                    <td class="am-text-middle am-text-left" colspan="6">
                                        <span class="am-margin-right-lg"> <?= $order['order_master']['create_time'] ?></span>
                                        <span class="am-margin-right-lg">订单号：<?= $order['order_master']['order_no'] ?></span>
                                    </td>
                                </tr>
                                <?php $i = 0;
                                foreach ($order['order_master']['goods'] as $goods): $i++; ?>
                                    <tr>
                                        <td class="goods-detail am-text-middle">
                                            <div class="goods-image">
                                                <img src="<?= $goods['image']['file_path'] ?>" alt="">
                                            </div>
                                            <div class="goods-info">
                                                <p class="goods-title"><?= $goods['goods_name'] ?></p>
                                                <p class="goods-spec am-link-muted">
                                                    <?= $goods['goods_attr'] ?>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="am-text-middle">
                                            <p>￥<?= $goods['goods_price'] ?></p>
                                            <p>×<?= $goods['total_num'] ?></p>
                                        </td>
                                        <?php if ($i === 1) : $goodsCount = count($order['order_master']['goods']); ?>
                                            <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <p>￥<?= $order['order_master']['pay_price'] ?></p>
                                                <p class="am-link-muted">
                                                    (含运费：￥<?= $order['order_master']['express_price'] ?>)</p>
                                            </td>
                                            <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <p><?= $order['order_master']['user']['nickName'] ?></p>
                                                <p class="am-link-muted">
                                                    (用户id：<?= $order['order_master']['user']['user_id'] ?>)</p>
                                            </td>
                                            <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <p>付款状态：
                                                    <span class="am-badge
                                                <?= $order['order_master']['pay_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                        <?= $order['order_master']['pay_status']['text'] ?></span>
                                                </p>
                                                <p>发货状态：
                                                    <span class="am-badge
                                                <?= $order['order_master']['delivery_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                        <?= $order['order_master']['delivery_status']['text'] ?></span>
                                                </p>
                                                <p>收货状态：
                                                    <span class="am-badge
                                                <?= $order['order_master']['receipt_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                        <?= $order['order_master']['receipt_status']['text'] ?></span>
                                                </p>
                                            </td>
                                            <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <?php if (!!$order['is_settled']) : ?>
                                                    <span class="am-badge am-badge-success">已结算</span>
                                                <?php else : ?>
                                                    <span class="am-badge">未结算</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="am-text-middle am-text-left" colspan="6">
                                        <div class="dealer am-cf">
                                            <?php if ($order['first_user_id'] > 0): ?>
                                                <div class="dealer-item am-fl am-margin-right-xl">
                                                    <p>
                                                        <span class="am-text-right">一级分销商：</span>
                                                        <span><?= $order['dealer_first']['user']['nickName'] ?>
                                                            (ID: <?= $order['dealer_first']['user_id'] ?>)</span>
                                                    </p>
                                                    <p>
                                                        <span class="am-text-right">分销佣金：</span>
                                                        <span class="x-color-red">￥<?= $order['first_money'] ?></span>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($order['second_user_id'] > 0): ?>
                                                <div class="dealer-item am-fl am-margin-right-xl">
                                                    <p>
                                                        <span class="am-text-right">二级分销商：</span>
                                                        <span><?= $order['dealer_second']['user']['nickName'] ?>
                                                            (ID: <?= $order['dealer_second']['user_id'] ?>)</span>
                                                    </p>
                                                    <p>
                                                        <span class="am-text-right">分销佣金：</span>
                                                        <span class="x-color-red">￥<?= $order['second_money'] ?></span>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($order['third_user_id'] > 0): ?>
                                                <div class="dealer-item am-fl am-margin-right-xl">
                                                    <p>
                                                        <span class="am-text-right">三级分销商：</span>
                                                        <span><?= $order['dealer_third']['user']['nickName'] ?>
                                                            (ID: <?= $order['dealer_third']['user_id'] ?>)</span>
                                                    </p>
                                                    <p>
                                                        <span class="am-text-right">分销佣金：</span>
                                                        <span class="x-color-red">￥<?= $order['third_money'] ?></span>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="am-text-center">暂无记录</td>
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
                </div>
            </div>
        </div>
    </div>
</div>

