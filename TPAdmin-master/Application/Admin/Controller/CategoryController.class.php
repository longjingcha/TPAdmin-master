<?php
namespace Admin\Controller;
use Extend\Page;
use Admin\Controller;

/**
 * 分类管理
 */
class CategoryController extends BaseController
{
    //require "../Application/Extend/Page.class.php";
    /**
     * 分类列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        if($key === ""){
            $model = M('category');  

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
            $model = M('category')->where($where); 
        } 
        
        $category = $model->where($where)->order('id')->limit($page->firstRow.','.$page->listRows)->select();
        //echo "<pre/>";var_dump($page, $Page);exit;
        // $this->assign('model',getSortedCategory($category));
        $this->assign('model',$category);

        //echo "<pre/>"; var_dump($model);exit;
        $this->assign('page_method',$show);
        $this->display();   
    }

    /**
     * 添加分类
     */
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('category')->select();
            //$cate = getSortedCategory($model);
            $cate=$model;

            $this->assign('cate',$cate);
           //echo "<pre/>"; print_r($cate);exit;
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("Category");
            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {

                if ($model->add()) {
                    $this->success("分类添加成功", U('category/index'));
                } else {
                    $this->error("分类添加失败");
                }
            }
        }
    }
    /**
     * 更新分类信息
     * @param  [type] $id [分类ID]
     * @return [type]     [description]
     */
    public function update()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('category')->find(I('id',"addslashes"));
          
            $this->assign('cate',getSortedCategory(M('category')->select()));
            $this->assign('model',$model);
            $this->display();
        }
        if (IS_POST) {
            $model = D("Category");
            if (!$model->create()) {
                $this->error($model->getError());
            }else{
             //   dd(I());die;
                if ($model->save()) {
                    $this->success("分类更新成功", U('category/index'));
                } else {
                    $this->error("分类更新失败");
                }        
            }
        }
    }
    /**
     * 删除分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete($id)
    {
    		$id = intval($id);
        $model = M('category');
        //查询属于这个分类的文章
        $posts = M('post')->where("cate_id= %d",$id)->select();
        if($posts){
            $this->error("禁止删除含有文章的分类");
        }
        //禁止删除含有子分类的分类
        $hasChild = $model->where("pid= %d",$id)->select();
        if($hasChild){
            $this->error("禁止删除含有子分类的分类");
        }
        //验证通过
        $result = $model->delete($id);
        if($result){
            $this->success("分类删除成功", U('category/index'));
        }else{
            $this->error("分类删除失败");
        }
    }
}
