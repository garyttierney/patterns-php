<?php

namespace tests\eLife\Patterns\ViewModel;

use DateTimeImmutable;
use eLife\Patterns\ViewModel\AnnotationTeaser;
use eLife\Patterns\ViewModel\Date;
use InvalidArgumentException;

final class AnnotationTeaserTest extends ViewModelTest
{
    /**
     * @test
     */
    public function it_has_data()
    {
        $data = [
            'document' => 'the document',
            'highlight' => 'this highlight',
            'content' => 'the content',
            'meta' => [
                'text' => 'Only me',
                'date' => [
                    'isExpanded' => false,
                    'isUpdated' => false,
                    'forHuman' => [
                        'dayOfMonth' => 21,
                        'month' => 'Dec',
                        'year' => 2017,
                    ],
                    'forMachine' => '2017-12-21',
                ],
            ],
            'inContextUri' => '#the-uri',
            'isRestricted' => true,
        ];

        $annotationTeaser = AnnotationTeaser::forAnnotation(
            $data['document'],
            Date::simple(new DateTimeImmutable('2017-12-21')),
            $data['inContextUri'],
            $data['highlight'],
            $data['content'],
            $data['isRestricted']);
        $this->assertSame($data['document'], $annotationTeaser['document']);
        $this->assertSame($data['highlight'], $annotationTeaser['highlight']);
        $this->assertSame($data['content'], $annotationTeaser['content']);
        $this->assertSame($data['inContextUri'], $annotationTeaser['inContextUri']);
        $this->assertSame($data['meta'], $annotationTeaser['meta']->toArray());

        unset($data['isRestricted']);
        $this->assertSameWithoutOrder($data, $annotationTeaser);
    }

    /**
     * @test
     */
    public function it_must_not_have_an_empty_document()
    {
        $this->expectException(InvalidArgumentException::class);

        AnnotationTeaser::forAnnotation(
            '',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            'the-uri',
            'the highlight',
            'the content'
        );
    }

    /**
     * @test
     */
    public function it_must_not_have_an_empty_in_context_uri()
    {
        $this->expectException(InvalidArgumentException::class);

        AnnotationTeaser::forAnnotation(
            'the document',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            '',
            'the highlight',
            'the content'
        );
    }

    /**
     * @test
     */
    public function a_full_version_must_have_a_highlight()
    {
        $this->expectException(InvalidArgumentException::class);

        AnnotationTeaser::forAnnotation(
            'the document',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            '#the-uri',
            '',
            'the content'
        );
    }

    /**
     * @test
     */
    public function a_full_version_must_have_content()
    {
        $this->expectException(InvalidArgumentException::class);

        AnnotationTeaser::forAnnotation('the document',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            '#the-uri',
            'highlight',
            ''
        );
    }

    /**
     * @test
     */
    public function a_highlight_must_have_a_highlight()
    {
        $this->expectException(InvalidArgumentException::class);

        AnnotationTeaser::forHighlight(
            'the document',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            '#the-uri',
            ''
        );
    }

    /**
     * @test
     */
    public function a_page_note_must_have_content()
    {
        $this->expectException(InvalidArgumentException::class);

        AnnotationTeaser::forPageNote('the document',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            '#the-uri',
            ''
        );
    }

    /**
     * @test
     */
    public function a_reply_must_have_content()
    {
        $this->expectException(InvalidArgumentException::class);

        AnnotationTeaser::forReply(
            'the document',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            '#the-uri',
            ''
        );
    }

    /**
     * @test
     */
    public function a_reply_must_have_isReply_set_to_true()
    {
        $annotationTeaser = AnnotationTeaser::forReply(
            'the document',
            Date::simple(new DateTimeImmutable('2017-12-21')),
            '#the-uri',
            'the content'
        );
        $this->assertTrue($annotationTeaser['isReply']);
    }

    public function viewModelProvider() : array
    {
        return [
            'highlight' => [
                AnnotationTeaser::forHighlight('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'highlight'),
            ],
            'restricted highlight' => [
                AnnotationTeaser::forHighlight('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'highlight', true),
            ],
            'page note' => [
                AnnotationTeaser::forPageNote('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'content'),
            ],
            'restricted page note' => [
                AnnotationTeaser::forPageNote('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'content', true),
            ],
            'reply' => [
                AnnotationTeaser::forReply('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'content'),
            ],
            'restricted reply' => [
                AnnotationTeaser::forReply('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'content', true),
            ],
            'full' => [
                AnnotationTeaser::forAnnotation('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'highlight', 'content'),
            ],
            'restricted full' => [
                AnnotationTeaser::forAnnotation('document', Date::simple(new DateTimeImmutable('2017-12-21')), '#the-uri', 'highlight', 'content', true),
            ],
        ];
    }

    protected function expectedTemplate() : string
    {
        return 'resources/templates/annotation-teaser.mustache';
    }
}
