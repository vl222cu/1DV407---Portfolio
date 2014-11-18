using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BlackJack.model.rules
{
    interface IWinGameRule
    {
        bool GetGameWinnerRule(model.Player a_dealer, model.Player a_player);
    }
}
