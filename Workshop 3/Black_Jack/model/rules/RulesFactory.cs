﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model.rules
{
    class RulesFactory
    {
        public IHitStrategy GetHitRule()
        {
            return new Soft17HitStrategy();
        }

        public INewGameStrategy GetNewGameRule()
        {
            return new SwedishNewGameStrategy();
        }

        public IWinGameRule GetGameWinnerRule()
        {
            return new UniqueRuleset();
        }
    }
}
