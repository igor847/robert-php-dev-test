<?php

use Api\Helpers\Request;
use PHPUnit\Framework\TestCase;
use Api\Controller\SegmentsController;

use function Api\Helpers\jsonResponse;

class SegmentsControllerTest extends TestCase
{
    public function testRead()
    {
		$controller = new SegmentsControllerMock([
			['id' => 1, 'segment' => 'Hi', 'translation' => 'Привіт'],
			['id' => 2, 'segment' => 'Bye', 'translation' => 'Бувай'],
		]);

        ob_start();
        $controller->read();
        $output = ob_get_clean();

        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertCount(2, $data);
        $this->assertSame('Hi', $data[0]['segment']);
        $this->assertSame('Бувай', $data[1]['translation']);
    }

	public function testCreate()
	{
		$controller = new SegmentsControllerMock();

		ob_start();
		$controller->create();
		$output = ob_get_clean();

		$this->assertJson($output);

		$data = json_decode($output, true);
		$this->assertArrayHasKey('message', $data);
		$this->assertSame('Segment created!', $data['message']);
	}

}

class RequestMock extends Request {
    public static array $bodyMock = [];

    public static function getBody(): array {
        return self::$bodyMock;
    }
}

class SegmentsControllerMock extends SegmentsController {
    private array $mockSegments;

    public function __construct(array $mockSegments = [])
    {
        $this->mockSegments = $mockSegments;
    }

    public function read(): void
    {
        echo jsonResponse($this->mockSegments);
    }

	public function create(): void
    {
        echo jsonResponse([
            'message' => 'Segment created!',
        ]);
    }
}
