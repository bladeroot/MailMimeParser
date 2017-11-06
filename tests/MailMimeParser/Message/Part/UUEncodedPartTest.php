<?php
namespace ZBateson\MailMimeParser\Message\Part;

use PHPUnit_Framework_TestCase;

/**
 * Description of UUEncodedPartTest
 *
 * @group UUEncodedPart
 * @group MessagePart
 * @covers ZBateson\MailMimeParser\Message\Part\UUEncodedPart
 * @author Zaahid Bateson
 */
class UUEncodedPartTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $pb = $this->getMockBuilder('ZBateson\MailMimeParser\Message\Part\PartBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $pb->expects($this->exactly(2))
            ->method('getProperty')
            ->willReturnCallback(function ($param) {
                $return = ['filename' => 'wubalubadubduuuuuub!', 'mode' => 0666];
                $this->assertArrayHasKey($param, $return);
                return $return[$param];
            });

        $part = new UUEncodedPart(
            'habibi',
            $pb
        );
        $this->assertFalse($part->isTextPart());
        $this->assertFalse($part->isMime());
        $this->assertEquals('application/octet-stream', $part->getContentType());
        $this->assertEquals('attachment', $part->getContentDisposition());
        $this->assertEquals('x-uuencode', $part->getContentTransferEncoding());
        $this->assertEquals(0666, $part->getUnixFileMode());
        $this->assertEquals('wubalubadubduuuuuub!', $part->getFilename());
    }
}
