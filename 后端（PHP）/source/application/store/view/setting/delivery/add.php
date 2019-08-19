<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div id="app" class="widget am-cf" v-cloak>
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl"><?= isset($model) ? '编辑' : '新增' ?>运费模版</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">模版名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="delivery[name]"
                                           v-model="name" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">计费方式 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="delivery[method]" value="10" data-am-ucheck
                                               v-model="method" checked> 按件数
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="delivery[method]" value="20" v-model="method"
                                               data-am-ucheck>
                                        按重量
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">
                                    配送区域及运费
                                </label>
                                <div class="am-u-sm-9 am-u-lg-10 am-u-end">
                                    <div class=" am-scrollable-horizontal">
                                        <table class="regional-table am-table am-table-bordered
                                         am-table-centered am-margin-bottom-xs">
                                            <tbody>
                                            <tr>
                                                <th width="42%">可配送区域</th>
                                                <th>
                                                    <span class="first">{{ method == 10 ? '首件 (个)' : '首重 (Kg)' }}</span>
                                                </th>
                                                <th>运费 (元)</th>
                                                <th>
                                                    <span class="additional">{{ method == 10 ? '续件 (个)' : '续重 (Kg)' }}</span>
                                                </th>
                                                <th>续费 (元)</th>
                                            </tr>
                                            <tr v-for="(item, formIndex) in forms">
                                                <td class="am-text-left">
                                                    <p class="selected-content am-margin-bottom-xs">
                                                        <span v-if="item.citys.length == <?= $cityCount ?>">全国</span>
                                                        <template v-else v-for="(province, index) in item.treeData">
                                                            <span>{{ province.name }}</span>
                                                            <template v-if="!province.isAllCitys">
                                                                (<span class="am-link-muted">
                                                                    <template v-for="(city, index) in province.citys">
                                                                        <span>{{ city.name }}</span><span
                                                                                v-if="(index + 1) < province.citys.length">、</span>
                                                                    </template>
                                                                </span>)
                                                            </template>
                                                        </template>
                                                    </p>
                                                    <p class="operation am-margin-bottom-xs">
                                                        <a class="edit" @click.stop="onEditerForm(formIndex, item)"
                                                           href="javascript:void(0);">编辑</a>
                                                        <a class="delete" href="javascript:void(0);"
                                                           @click.stop="onDeleteForm(formIndex)">删除</a>
                                                    </p>
                                                    <input type="hidden" name="delivery[rule][region][]"
                                                           :value="item.citys" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="delivery[rule][first][]"
                                                           v-model="item.first" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="delivery[rule][first_fee][]"
                                                           v-model="item.first_fee" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="delivery[rule][additional][]"
                                                           v-model="item.additional">
                                                </td>
                                                <td>
                                                    <input type="number" name="delivery[rule][additional_fee][]"
                                                           v-model="item.additional_fee">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="am-text-left">
                                                    <a class="add-region am-btn am-btn-default am-btn-xs"
                                                       href="javascript:void(0);" @click.stop="onAddRegionEvent">
                                                        <i class="iconfont icon-dingwei"></i>
                                                        点击添加可配送区域和运费
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" class="tpl-form-input" name="delivery[sort]"
                                           value="100" required>
                                    <small>数字越小越靠前</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary"> 提交
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>

                <!-- 地区选择 -->
                <div ref="choice" class="regional-choice">
                    <div class="place-div">
                        <div>
                            <div class="checkbtn">
                                <label>
                                    <input type="checkbox" @change="onCheckAll(!checkAll)" :checked="checkAll">
                                    全选</label>
                                <a class="clearCheck" href="javascript:void(0);" @click="onCheckAll(false)">清空</a>
                            </div>
                            <div class="place clearfloat">
                                <div class="smallplace clearfloat">
                                    <div v-for="item in regions"
                                         v-if="!isPropertyExist(item.id, disable.treeData) || !disable.treeData[item.id].isAllCitys"
                                         class="place-tooltips">
                                        <label>
                                            <input type="checkbox" class="province"
                                                   :value="item.id"
                                                   :checked="inArray(item.id, checked.province)"
                                                   @change="onCheckedProvince">
                                            <span class="province_name">{{ item.name }}</span><span
                                                    class="ratio"></span>
                                        </label>
                                        <div class="citys">
                                            <i class="jt"><i></i></i>
                                            <div class="row-div clearfloat">
                                                <p v-for="city in item.city"
                                                   v-if="!inArray(city.id, disable.citys)">
                                                    <label>
                                                        <input class="city" type="checkbox"
                                                               :value="city.id"
                                                               :checked="inArray(city.id, checked.citys)"
                                                               @change="onCheckedCity($event, item.id)">
                                                        <span>{{ city.name }}</span>
                                                    </label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="assets/common/js/vue.min.js?v=<?= $version ?>"></script>
<script src="assets/store/js/delivery.js?v=<?= $version ?>"></script>
<script>
    $(function () {

        new delivery({
            el: '#app',
            name: "<?= isset($model) ? $model['name'] : '' ?>",
            method: <?= isset($model) ? $model['method']['value'] : 10 ?>,
            regions: JSON.parse('<?= $regionData ?>'),
            cityCount: <?= $cityCount ?>,
            formData: JSON.parse('<?= isset($formData) ? $formData : '[]' ?>')
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
