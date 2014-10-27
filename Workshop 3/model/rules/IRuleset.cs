using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model.rules
{
    interface IRuleset
    {
        void setFields(model.Player a_player, model.Dealer a_dealer, int g_maxScore);
        bool isDealerWinner(model.Player a_player, model.Dealer a_dealer, int g_maxScore);
    }
}
