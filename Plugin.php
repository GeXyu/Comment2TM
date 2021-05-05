<?php
/**
 * 甜沫推送评论插件
 * @package Comment2TM 
 * @author tm
 * @version 1.0.0
 * @link https://blog.tnt.pub
 */
class Comment2TM_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {

        Typecho_Plugin::factory('Widget_Feedback')->comment = array('Comment2TM_Plugin', 'tm_send');
        Typecho_Plugin::factory('Widget_Feedback')->trackback = array('Comment2TM_Plugin', 'tm_send');
        Typecho_Plugin::factory('Widget_XmlRpc')->pingback = array('Comment2TM_Plugin', 'tm_send');
        
        return _t('请配置此插件的 tmkey, 以使您的推送生效');
    }
    
    /**
     * 禁用插件方法
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $key = new Typecho_Widget_Helper_Form_Element_Text('tmkey', NULL, NULL, _t('TMKEY'), _t('TMKEY 需要在 <a href="https://tm.tnt.pub/">甜沫推送</a> 注册<br />
        同时，注册后需要在 <a href="https://tm.tnt.pub/setting/">推送配置</a> 中配置您的推送通道，才能推送到微信，QQ，TG等'));
        $form->addInput($key->addRule('required', _t('您必须填写一个正确的 TMKEY')));
    }
    
    /**
     * 个人用户的配置面板
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 甜沫推送推送
     */
    public static function tm_send($comment, $post)
    {
        $options = Typecho_Widget::widget('Widget_Options');
        $tmkey = $options->plugin('Comment2TM')->tmkey;

		//
        $title = Helper::options()->title." 上有新的评论";
        $content  = "".$comment['author']." 同学在文章《".$post->title."》中给您的留言了";

        //
        $result = file_get_contents('https://api.tnt.pub/send/'.$tmkey.'?title='.urlencode($title).'&content='.urlencode($content));
        return  $comment;
    }
}
