<?php

describe('Persistence\Hydrator\Strategy\DateStrategy', function() {
    beforeEach(function() {
        $this->strategy = new DateStrategy();
    });

    describe('->hydrate()', function() {
        it('should turn the string date into a DateTime object', function() {
            $value = '2014-12-26';
            $obj = $this->strategy->hydrate($value);

            assert($obj->format('Y-m-d') === $value, 'incorrect datetime');
        });
    });

    describe('->extract()', function() {
        it('should turn the DateTime into a string', function() {
            $value = new \DateTime('2014-12-28');

            $string = $this->strategy->extract($value);

            assert($string === $value->format('Y-m-d'));
        });
    });
});
