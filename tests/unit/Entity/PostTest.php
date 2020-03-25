<?php declare(strict_types = 1);

use App\Entity\Post;
use Codeception\Test\Unit;

class PostTest extends Unit
{
    protected Post $post;

    protected function _before()
    {
        try {
            $this->post = new Post([
                'id' => 'post5e68609a29ea7_6eeaa679',
                'from_name' => 'Nydia Croff',
                "from_id" => "user_2",
                'message' => 'post_1',
                'type' => 'status',
                'created_time' => '2020-03-11T00:34:23+00:00',
            ]);
        } catch (\Exception $e) {
            // TODO handle this case
        }
    }

    public function testGetId()
    {
        $this->assertEquals('post5e68609a29ea7_6eeaa679', $this->post->getId());
    }

    public function testFromName()
    {
        $this->assertEquals('Nydia Croff', $this->post->getFromName());
    }

    public function testFromId()
    {
        $this->assertEquals('user_2', $this->post->getFromId());
    }

    public function testGetMessage()
    {
        $this->assertEquals('post_1', $this->post->getMessage());
    }

    public function testGetType()
    {
        $this->assertEquals('status', $this->post->getType());
    }

    public function testCreatedTime()
    {
        $this->assertEquals(new DateTime('2020-03-11T00:34:23+00:00'), $this->post->getCreatedTime());
    }
}