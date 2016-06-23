<?php
namespace Admin\Controller;
use Admin\Controller;
use Extend\Page;
/**
 * 单页管理
 */
class PageController extends BaseController
{
    /**
     * 单页列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        if($key === ""){
            $model = M('page');  

            $count=$model->count();
            import('ORG.Util.Page');
            $page=new Page($count,8);
            $page->setConfig('prev',"Previous");
            $page->setConfig('next', 'Next ');//下一页
            $page->setConfig('first', ' First');//第一页
            $page->setConfig('last', 'Last ');//最后一页    
            //$page->setConfig('theme',' %FIRST% %UPPAGE%  %linkPage%  %downPage% %end%');
            //$page->setConfig('theme','%FIRST% %upPage% %linkPage% %downPage% %end%  共%totalRow%条数据 ');
            $show=$page->show();
        }else{
            $where['title'] = array('like',"%$key%");
            $where['name'] = array('like',"%$key%");
            $where['_logic'] = 'or';
            $model = M('page')->where($where); 
        } 
        
        $count  = $model->where($where)->count();// 查询满足要求的总记录数
        //$Page = new \Extend\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //$show = $Page->show();// 分页显示输出
        $pages = $model->where($where)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('model', $pages);
        $this->assign('page_method',$show);
        $this->display();     
    }

    /**
     * 添加单页
     */
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("Page");
            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($model->add()) {
                    $this->success("添加成功", U('page/index'));
                } else {
                    $this->error("添加失败");
                }
            }
        }
    }
    /**
     * 更新单页信息
     * @param  [type] $id [单页ID]
     * @return [type]     [description]
     */
    public function update($id)
    {
    		$id = intval($id);
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('page')->where("id=%d",$id)->find();
            $this->assign('page',$model);
            $this->display();
        }
        if (IS_POST) {
            $model = D("Page");
            if (!$model->create()) {
                $this->error($model->getError());
            }else{
                if ($model->save()) {
                    $this->success("更新成功", U('page/index'));
                } else {
                    $this->error("更新失败");
                }        
            }
        }
    }
    /**
     * 删除单页
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete($id)
    {
    		$id = intval($id);
        $model = M('page');
        $result = $model->where("id=%d",$id)->delete();
        if($result){
            $this->success("删除成功", U('page/index'));
        }else{
            $this->error("删除失败");
        }
    }
}
