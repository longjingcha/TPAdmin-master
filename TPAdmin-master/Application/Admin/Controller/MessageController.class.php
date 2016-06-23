<?php
namespace Admin\Controller;
use Admin\Controller;
use Extend\Page;
/**
 * 文章管理
 */
class MessageController extends BaseController
{
    /**
     * 文章列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        if($key === ""){
            $model = D('PostView'); 

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
            $where['post.title'] = array('like',"%$key%");
            $where['member.username'] = array('like',"%$key%");
            $where['category.title'] = array('like',"%$key%");
            $where['_logic'] = 'or';
            $model = D('PostView')->where($where); 
        } 
        
        $count  = $model->where($where)->count();// 查询满足要求的总记录数
       // $Page = new \Extend\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
       // $show = $Page->show();// 分页显示输出
        $post = $model->where($where)->order('post.id')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('model', $post);
         $this->assign('page_method',$show);
       // $this->assign('page',$show);
        $this->display();     
    }
    /**
     * 添加文章
     */
    public function add()
    {
    
    }
    /**
     * 更新文章信息
     * @param  [type] $id [文章ID]
     * @return [type]     [description]
     */
    public function update($id)
    {
    
    }
    /**
     * 删除文章
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete($id)
    {
    	
	}
}
