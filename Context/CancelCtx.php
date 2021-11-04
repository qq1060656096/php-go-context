<?php
namespace Zwei\Context;


class CancelCtx implements Context,Canceler
{
    use ContextTrait;
    use toStringTrait;

    protected $done = false;

    /**
     * @var \Exception|null
     */
    protected $err = null;

    protected $children = [];

    /**
     * @inheritDoc
     */
    public function Value($key)
    {
        if ($key === cancelCtxKey()) {
            return $this;
        }

        if (!$this->parent) {
            return null;
        }
        return $this->parent->Value($key);
    }

    /**
     * @inheritDoc
     */
    public function Done()
    {
        if ($this->done) {
            return true;
        }
        return false;
    }

    /**
     * @return \Exception|null
     */
    public function Err()
    {
        if ($this->err) {
            return $this->err;
        }
        return null;
    }

    public function String() {
        return sprintf("%s:WithCancel", (string)$this);
    }

    /**
     * 取消每个子节点，如果 $removeFromParent 是 true 将当前 context 从父 context 中移除
     * @param bool $removeFromParent
     * @param \Exception $err
     * @return mixed|void
     * @throws \Exception
     */
    public function Cancel($removeFromParent, \Exception $err)
    {
        if (is_null($err)) {
            throw new \Exception("err 参数错误");
        }
        $this->done = true;
        $this->err = $err;
        foreach ($this->children as $child) {
            $child->Cancel(false, $err);
        }
        $this->children = [];
        if ($removeFromParent) {
            removeChild($this->parent, $this);
        }
    }

    /**
     * 获取子节点
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * 添加子节点
     *
     * @param Canceler $canceler
     */
    public function addChildren(Canceler $canceler) {
        $this->children[] = $canceler;
    }

    /**
     * @param integer $id
     * @param Canceler $canceler
     * @return bool
     */
    public function deleteChildren($id, Canceler $canceler) {
        $child = $this->children[$id];
        if ($child === $canceler) {
            unset($this->children[$id]);
            return true;
        }
        return false;
    }

    /**
     * @param Context $parent
     * @return static
     */
    public static function newCancelCtx(Context $parent) {
        $ctx = new static();
        $ctx->parent = $parent;
        return $ctx;
    }

}