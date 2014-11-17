using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model.rules
{
    class RulesFactory
    {
        public int _gameStrategyChoise;

        public RulesFactory(int _gameStrategyChoise)
        {
            this._gameStrategyChoise = _gameStrategyChoise;
        }

        public IHitStrategy GetHitRule()
        {
            return new Soft17HitStrategy();
        }

        public INewGameStrategy GetNewGameRule()
        {
            if (_gameStrategyChoise == 1)
            {
                return new SwedishNewGameStrategy();
            } 
            else if (_gameStrategyChoise == 2) 
            {
                return new AmericanNewGameStrategy();
            }
            else 
            {
                return new InternationalNewGameStrategy();
            }
            
        }
    }
}
