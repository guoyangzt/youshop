<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <div class="am-tabs am-margin-top" data-am-tabs="{noSwipe: 1}">
                            <ul class="am-tabs-nav am-nav am-nav-tabs">
                                <li class="am-active"><a href="#tab1">基础设置</a></li>
                                <li><a href="#tab2">分销商条件</a></li>
                                <li><a href="#tab3">佣金设置</a></li>
                                <li><a href="#tab4">结算</a></li>
                                <li><a href="#tab5">自定义文字</a></li>
                                <li><a href="#tab6">申请协议</a></li>
                                <li><a href="#tab7">页面背景图</a></li>
                                <li><a href="#tab8">模板消息</a></li>
                            </ul>
                            <div class="am-tabs-bd">
                                <div class="am-tab-panel am-active am-margin-top-lg" id="tab1">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require"> 是否开启分销功能 </label>
                                        <div class="am-u-sm-9">
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[basic][is_open]"
                                                       value="1" data-am-ucheck
                                                    <?= $data['basic']['values']['is_open'] == '1' ? 'checked' : '' ?>>
                                                开启
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[basic][is_open]"
                                                       value="0" data-am-ucheck
                                                    <?= $data['basic']['values']['is_open'] == '0' ? 'checked' : '' ?>>
                                                关闭
                                            </label>
                                        </div>
                                    </div>
                                    <div class="am-form-group am-padding-top">
                                        <label class="am-u-sm-3 am-form-label form-require"> 分销层级 </label>
                                        <div class="am-u-sm-9">
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[basic][level]"
                                                       value="1" data-am-ucheck
                                                    <?= $data['basic']['values']['level'] == '1' ? 'checked' : '' ?>>
                                                一级分销
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[basic][level]"
                                                       value="2" data-am-ucheck
                                                    <?= $data['basic']['values']['level'] == '2' ? 'checked' : '' ?>>
                                                二级分销
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[basic][level]"
                                                       value="3" data-am-ucheck
                                                    <?= $data['basic']['values']['level'] == '3' ? 'checked' : '' ?>>
                                                三级分销
                                            </label>
                                        </div>
                                    </div>
                                    <div class="am-form-group am-padding-top">
                                        <label class="am-u-sm-3 am-form-label form-require"> 分销商内购 </label>
                                        <div class="am-u-sm-9">
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[basic][self_buy]"
                                                       value="1" data-am-ucheck
                                                    <?= $data['basic']['values']['self_buy'] == '1' ? 'checked' : '' ?>>
                                                开启
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[basic][self_buy]"
                                                       value="0" data-am-ucheck
                                                    <?= $data['basic']['values']['self_buy'] == '0' ? 'checked' : '' ?>>
                                                关闭
                                            </label>
                                            <div class="help-block">
                                                <small>如开启，分销商自己购买商品，获得一级佣金</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-tab-panel am-margin-top-lg" id="tab2">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require"> 成为分销商条件 </label>
                                        <div class="am-u-sm-9">
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[condition][become]"
                                                       value="10" data-am-ucheck
                                                    <?= $data['condition']['values']['become'] == '10' ? 'checked' : '' ?>>
                                                需后台审核
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[condition][become]"
                                                       value="20" data-am-ucheck
                                                    <?= $data['condition']['values']['become'] == '20' ? 'checked' : '' ?>>
                                                无需审核
                                            </label>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require"> 购买指定商品成为分销商 </label>
                                        <div class="am-u-sm-9">
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[condition][become__buy_goods]"
                                                       value="0" data-am-ucheck
                                                    <?= $data['condition']['values']['become__buy_goods'] == '0' ? 'checked' : '' ?>>
                                                关闭
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[condition][become__buy_goods]"
                                                       value="1" data-am-ucheck
                                                    <?= $data['condition']['values']['become__buy_goods'] == '1' ? 'checked' : '' ?>>
                                                开启
                                            </label>
                                            <div class="help-block">
                                                <small>购买指定商品付款后自动成为分销商，无需后台审核</small>
                                            </div>
                                            <div class="widget-become-goods am-form-file am-margin-top-xs
                                                <?= $data['condition']['values']['become__buy_goods'] == '0' ? 'hide' : '' ?>">
                                                <button type="button"
                                                        class="j-selectGoods upload-file am-btn am-btn-secondary am-radius">
                                                    <i class="am-icon-cloud-upload"></i> 选择商品
                                                </button>
                                                <div class="widget-goods-list uploader-list am-cf">
                                                    <?php if (!$goodsList->isEmpty()): foreach ($goodsList as $goods): ?>
                                                        <div class="file-item">
                                                            <a href="<?= $goods['image'][0]['file_path'] ?>"
                                                               title="<?= $goods['goods_name'] ?>" target="_blank">
                                                                <img src="<?= $goods['image'][0]['file_path'] ?>">
                                                            </a>
                                                            <input type="hidden"
                                                                   name="setting[condition][become__buy_goods_ids][]"
                                                                   value="<?= $goods['goods_id'] ?>">
                                                            <i class="iconfont icon-shanchu file-item-delete"
                                                               data-name="商品"></i>
                                                        </div>
                                                    <?php endforeach; endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="am-form-group am-padding-top">
                                        <label class="am-u-sm-3 am-form-label form-require"> 成为下线条件 </label>
                                        <div class="am-u-sm-9">
                                            <label class="am-radio-inline">
                                                <input type="radio" name="setting[condition][downline]"
                                                       value="10" data-am-ucheck
                                                    <?= $data['condition']['values']['downline'] == '10' ? 'checked' : '' ?>>
                                                首次点击分享链接
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-tab-panel am-margin-top-lg" id="tab3">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            一级佣金比例
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="number" min="0" max="100" class="tpl-form-input"
                                                   name="setting[commission][first_money]"
                                                   value="<?= $data['commission']['values']['first_money'] ?>" required>
                                            <small>佣金比例范围 0% - 100%</small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            二级佣金比例
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="number" min="0" max="100" class="tpl-form-input"
                                                   name="setting[commission][second_money]"
                                                   value="<?= $data['commission']['values']['second_money'] ?>"
                                                   required>
                                            <small>佣金比例范围 0% - 100%</small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            三级佣金比例
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="number" min="0" max="100" class="tpl-form-input"
                                                   name="setting[commission][third_money]"
                                                   value="<?= $data['commission']['values']['third_money'] ?>" required>
                                            <small>佣金比例范围 0% - 100%</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-tab-panel am-margin-top-lg" id="tab4">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require"> 提现方式 </label>
                                        <div class="am-u-sm-9">
                                            <label class="am-checkbox-inline">
                                                <input type="checkbox" name="setting[settlement][pay_type][]" value="10"
                                                       data-am-ucheck
                                                    <?= in_array('10', $data['settlement']['values']['pay_type']) ? 'checked' : '' ?>>
                                                微信支付
                                            </label>
                                            <label class="am-checkbox-inline">
                                                <input type="checkbox" name="setting[settlement][pay_type][]" value="20"
                                                       data-am-ucheck
                                                    <?= in_array('20', $data['settlement']['values']['pay_type']) ? 'checked' : '' ?>>
                                                支付宝
                                            </label>
                                            <label class="am-checkbox-inline">
                                                <input type="checkbox" name="setting[settlement][pay_type][]" value="30"
                                                       data-am-ucheck
                                                    <?= in_array('30', $data['settlement']['values']['pay_type']) ? 'checked' : '' ?>>
                                                银行卡
                                            </label>
                                            <div class="help-block">
                                                <small>注：如使用微信支付，则需申请微信支付企业付款到零钱功能</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            最低提现额度
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="number" min="0" class="tpl-form-input"
                                                   name="setting[settlement][min_money]"
                                                   value="<?= $data['settlement']['values']['min_money'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group am-padding-top">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            佣金结算天数
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="number" min="0" class="tpl-form-input"
                                                   name="setting[settlement][settle_days]"
                                                   value="<?= $data['settlement']['values']['settle_days'] ?>"
                                                   required>
                                            <div class="help-block">
                                                <small>当订单完成n天后，该订单的分销佣金才会结算到分销商余额，如果设置为0天 则订单完成时就结算</small>
                                            </div>
                                            <div class="help-block">
                                                <small>注：建议佣金结算天数大于允许发起售后申请天数，如果用户申请退款退货 则不结算佣金</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-tab-panel" id="tab5">
                                    <div class="widget-head am-cf">
                                        <div class="widget-title am-fl">分销中心页面</div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            页面标题
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][title][value]"
                                                   value="<?= $data['words']['values']['index']['title']['value'] ?>"
                                                   required>
                                            <small>
                                                默认：<?= $data['words']['values']['index']['title']['default'] ?></small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            非分销商提示
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][words][not_dealer][value]"
                                                   value="<?= $data['words']['values']['index']['words']['not_dealer']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            申请成为分销商
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][words][apply_now][value]"
                                                   value="<?= $data['words']['values']['index']['words']['apply_now']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            推荐人
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][words][referee][value]"
                                                   value="<?= $data['words']['values']['index']['words']['referee']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            可提现佣金
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][words][money][value]"
                                                   value="<?= $data['words']['values']['index']['words']['money']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            待提现佣金
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][words][freeze_money][value]"
                                                   value="<?= $data['words']['values']['index']['words']['freeze_money']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            已提现金额
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][words][total_money][value]"
                                                   value="<?= $data['words']['values']['index']['words']['total_money']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            去提现
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][index][words][withdraw][value]"
                                                   value="<?= $data['words']['values']['index']['words']['withdraw']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="widget-head am-cf">
                                        <div class="widget-title am-fl">申请成为分销商页面</div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            页面标题
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][apply][title][value]"
                                                   value="<?= $data['words']['values']['apply']['title']['value'] ?>"
                                                   required>
                                            <small>
                                                默认：<?= $data['words']['values']['apply']['title']['default'] ?></small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            请填写申请信息
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][apply][words][title][value]"
                                                   value="<?= $data['words']['values']['apply']['words']['title']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            分销商申请协议
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][apply][words][license][value]"
                                                   value="<?= $data['words']['values']['apply']['words']['license']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            申请成为经销商
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][apply][words][submit][value]"
                                                   value="<?= $data['words']['values']['apply']['words']['submit']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            审核中提示信息
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][apply][words][wait_audit][value]"
                                                   value="<?= $data['words']['values']['apply']['words']['wait_audit']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            去商城逛逛
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][apply][words][goto_mall][value]"
                                                   value="<?= $data['words']['values']['apply']['words']['goto_mall']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="widget-head am-cf">
                                        <div class="widget-title am-fl">分销订单页面</div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            页面标题
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][order][title][value]"
                                                   value="<?= $data['words']['values']['order']['title']['value'] ?>"
                                                   required>
                                            <small>
                                                默认：<?= $data['words']['values']['order']['title']['default'] ?></small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            全部
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][order][words][all][value]"
                                                   value="<?= $data['words']['values']['order']['words']['all']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            未结算
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][order][words][unsettled][value]"
                                                   value="<?= $data['words']['values']['order']['words']['unsettled']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            已结算
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][order][words][settled][value]"
                                                   value="<?= $data['words']['values']['order']['words']['settled']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="widget-head am-cf">
                                        <div class="widget-title am-fl">我的团队页面</div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            页面标题
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][team][title][value]"
                                                   value="<?= $data['words']['values']['team']['title']['value'] ?>"
                                                   required>
                                            <small>
                                                默认：<?= $data['words']['values']['team']['title']['default'] ?></small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            团队总人数
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][team][words][total_team][value]"
                                                   value="<?= $data['words']['values']['team']['words']['total_team']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            一级团队
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][team][words][first][value]"
                                                   value="<?= $data['words']['values']['team']['words']['first']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            二级团队
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][team][words][second][value]"
                                                   value="<?= $data['words']['values']['team']['words']['second']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            三级团队
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][team][words][third][value]"
                                                   value="<?= $data['words']['values']['team']['words']['third']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="widget-head am-cf">
                                        <div class="widget-title am-fl">提现明细页面</div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            页面标题
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_list][title][value]"
                                                   value="<?= $data['words']['values']['withdraw_list']['title']['value'] ?>"
                                                   required>
                                            <small>
                                                默认：<?= $data['words']['values']['withdraw_list']['title']['default'] ?></small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            全部
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_list][words][all][value]"
                                                   value="<?= $data['words']['values']['withdraw_list']['words']['all']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            审核中
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_list][words][apply_10][value]"
                                                   value="<?= $data['words']['values']['withdraw_list']['words']['apply_10']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            审核通过
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_list][words][apply_20][value]"
                                                   value="<?= $data['words']['values']['withdraw_list']['words']['apply_20']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            已打款
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_list][words][apply_40][value]"
                                                   value="<?= $data['words']['values']['withdraw_list']['words']['apply_40']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            驳回
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_list][words][apply_30][value]"
                                                   value="<?= $data['words']['values']['withdraw_list']['words']['apply_30']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="widget-head am-cf">
                                        <div class="widget-title am-fl">申请提现页面</div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            页面标题
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_apply][title][value]"
                                                   value="<?= $data['words']['values']['withdraw_apply']['title']['value'] ?>"
                                                   required>
                                            <small>
                                                默认：<?= $data['words']['values']['withdraw_apply']['title']['default'] ?></small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            可提现佣金
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_apply][words][capital][value]"
                                                   value="<?= $data['words']['values']['withdraw_apply']['words']['capital']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            提现金额
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_apply][words][money][value]"
                                                   value="<?= $data['words']['values']['withdraw_apply']['words']['money']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            请输入要提取的金额
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_apply][words][money_placeholder][value]"
                                                   value="<?= $data['words']['values']['withdraw_apply']['words']['money_placeholder']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            最低提现佣金
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_apply][words][min_money][value]"
                                                   value="<?= $data['words']['values']['withdraw_apply']['words']['min_money']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            提交申请
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][withdraw_apply][words][submit][value]"
                                                   value="<?= $data['words']['values']['withdraw_apply']['words']['submit']['value'] ?>"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="widget-head am-cf">
                                        <div class="widget-title am-fl">推广二维码</div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            页面标题
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[words][qrcode][title][value]"
                                                   value="<?= $data['words']['values']['qrcode']['title']['value'] ?>"
                                                   required>
                                            <small>
                                                默认：<?= $data['words']['values']['qrcode']['title']['default'] ?></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="am-tab-panel am-margin-top-lg" id="tab6">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">分销商申请协议 </label>
                                        <div class="am-u-sm-9 am-u-end">
                                    <textarea class="" rows="6" placeholder="请输入分销商申请协议"
                                              name="setting[license][license]"><?= $data['license']['values']['license'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-tab-panel am-margin-top-lg" id="tab7">
                                    <input type="hidden" name="setting[background][__]" value="">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">分销中心首页 </label>
                                        <div class="am-u-sm-9 am-u-end">
                                            <div class="am-form-file">
                                                <div class="am-form-file">
                                                    <button type="button"
                                                            class="j-index upload-file am-btn am-btn-secondary am-radius">
                                                        <i class="am-icon-cloud-upload"></i> 选择图片
                                                    </button>
                                                    <div class="uploader-list am-cf">
                                                        <?php if (!empty($data['background']['values']['index'])): ?>
                                                            <div class="file-item">
                                                                <a href="<?= $data['background']['values']['index'] ?>"
                                                                   title="点击查看大图"
                                                                   target="_blank">
                                                                    <img src="<?= $data['background']['values']['index'] ?>">
                                                                </a>
                                                                <input type="hidden" name="setting[background][index]"
                                                                       value="<?= $data['background']['values']['index'] ?>">
                                                                <i class="iconfont icon-shanchu file-item-delete"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="help-block">
                                                    <small>尺寸：宽750像素 高度不限</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">申请成为分销商页 </label>
                                        <div class="am-u-sm-9 am-u-end">
                                            <div class="am-form-file">
                                                <div class="am-form-file">
                                                    <button type="button"
                                                            class="j-apply upload-file am-btn am-btn-secondary am-radius">
                                                        <i class="am-icon-cloud-upload"></i> 选择图片
                                                    </button>
                                                    <div class="uploader-list am-cf">
                                                        <?php if (!empty($data['background']['values']['apply'])): ?>
                                                            <div class="file-item">
                                                                <a href="<?= $data['background']['values']['apply'] ?>"
                                                                   title="点击查看大图"
                                                                   target="_blank">
                                                                    <img src="<?= $data['background']['values']['apply'] ?>">
                                                                </a>
                                                                <input type="hidden" name="setting[background][apply]"
                                                                       value="<?= $data['background']['values']['apply'] ?>">
                                                                <i class="iconfont icon-shanchu file-item-delete"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="help-block">
                                                    <small>尺寸：宽750像素 高度不限</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">申请提现页 </label>
                                        <div class="am-u-sm-9 am-u-end">
                                            <div class="am-form-file">
                                                <div class="am-form-file">
                                                    <button type="button"
                                                            class="j-withdraw_apply upload-file am-btn am-btn-secondary am-radius">
                                                        <i class="am-icon-cloud-upload"></i> 选择图片
                                                    </button>
                                                    <div class="uploader-list am-cf">
                                                        <?php if (!empty($data['background']['values']['withdraw_apply'])): ?>
                                                            <div class="file-item">
                                                                <a href="<?= $data['background']['values']['withdraw_apply'] ?>"
                                                                   title="点击查看大图"
                                                                   target="_blank">
                                                                    <img src="<?= $data['background']['values']['withdraw_apply'] ?>">
                                                                </a>
                                                                <input type="hidden"
                                                                       name="setting[background][withdraw_apply]"
                                                                       value="<?= $data['background']['values']['withdraw_apply'] ?>">
                                                                <i class="iconfont icon-shanchu file-item-delete"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="help-block">
                                                    <small>尺寸：宽750像素 高度不限</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-tab-panel am-margin-top-lg" id="tab8">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            分销商入驻审核通知
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[template_msg][apply_tpl]"
                                                   placeholder="请填写模板消息ID"
                                                   value="<?= $data['template_msg']['values']['apply_tpl'] ?>">
                                            <small>模板编号AT0674，关键词 (申请时间、审核状态、审核时间、备注信息)</small>
                                            <small class="am-margin-left-xs">
                                                <a href="<?= url('store/setting.help/tplmsg') ?>" target="_blank">如何获取模板消息ID？</a>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label form-require">
                                            提现状态通知
                                        </label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input"
                                                   name="setting[template_msg][withdraw_tpl]"
                                                   placeholder="请填写模板消息ID"
                                                   value="<?= $data['template_msg']['values']['withdraw_tpl'] ?>">
                                            <small>模板编号AT0324，关键词 (提现时间、提现方式、提现金额、提现状态、备注)</small>
                                            <small class="am-margin-left-xs">
                                                <a href="<?= url('store/setting.help/tplmsg') ?>" target="_blank">如何获取模板消息ID？</a>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 图片文件列表模板 -->
<script id="tpl-file-item" type="text/template">
    {{ each list }}
    <div class="file-item">
        <a href="{{ $value.file_path }}" title="点击查看大图" target="_blank">
            <img src="{{ $value.file_path }}">
        </a>
        <input type="hidden" name="{{ name }}" value="{{ $value.file_path }}">
        <i class="iconfont icon-shanchu file-item-delete" data-name="商品"></i>
    </div>
    {{ /each }}
</script>

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<!-- 商品列表 -->
<script id="tpl-goods-list-item" type="text/template">
    {{ each $data }}
    <div class="file-item">
        <a href="{{ $value.image }}" title="{{ $value.goods_name }}" target="_blank">
            <img src="{{ $value.image }}">
        </a>
        <input type="hidden" name="setting[condition][become__buy_goods_ids][]" value="{{ $value.goods_id }}">
        <i class="iconfont icon-shanchu file-item-delete" data-name="商品"></i>
    </div>
    {{ /each }}
</script>

<script src="assets/common/js/ddsort.js"></script>
<script src="assets/store/js/select.data.js?v=<?= $version ?>"></script>
<script>
    $(function () {

        // 选择图片：分销中心首页
        $('.j-index').selectImages({
            name: 'setting[background][index]'
            , multiple: false
        });

        // 选择图片：申请成为分销商页
        $('.j-apply').selectImages({
            name: 'setting[background][apply]'
            , multiple: false
        });

        // 选择图片：申请提现页
        $('.j-withdraw_apply').selectImages({
            name: 'setting[background][withdraw_apply]'
            , multiple: false
        });

        // 购买指定商品成为分销商：选择商品
        var $goodsList = $('.widget-goods-list');
        $('.j-selectGoods').selectData({
            title: '选择商品',
            uri: 'goods/lists&status=10',
            duplicate: false,
            dataIndex: 'goods_id',
            done: function (data) {
                if (this.getExistData().length + data.length > 5) {
                    layer.msg('选择商品的数量不能超过5个', {anim: 6});
                    return;
                }
                var $html = $(template('tpl-goods-list-item', data));
                // 删除文件
                $html.find('.file-item-delete').on('click', function () {
                    $(this).parent().remove();
                });
                $goodsList.append($html);
            },
            getExistData: function () {
                var data = [];
                $("input[name='setting[condition][become__buy_goods_ids][]']")
                    .each(function (index, item) {
                        data.push(item.value);
                    });
                return data;
            }
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

        // 购买指定商品成为分销商  是否开启
        $("input:radio[name='setting[condition][become__buy_goods]']").change(function(e) {
            var $content = $('.widget-become-goods');
            e.currentTarget.value == '0' ? $content.hide() : $content.show();
        });
            
    });
</script>
