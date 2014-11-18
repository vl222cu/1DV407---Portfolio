using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model.rules
{
    class UniqueRuleset : IWinGameRule
    {
        private const int g_maxScore = 21;

        public bool GetGameWinnerRule(model.Player a_dealer, model.Player a_player)
        {
            if (a_dealer.CalcScore() > g_maxScore && a_player.CalcScore() > g_maxScore && a_dealer.CalcScore() < a_player.CalcScore())
            {
                return false;
            }

            else if (a_dealer.CalcScore() > g_maxScore && a_player.CalcScore() > g_maxScore && a_dealer.CalcScore() > a_player.CalcScore())
            {
                return true;
            }

            else if (a_dealer.CalcScore() <= g_maxScore && a_player.CalcScore() > g_maxScore)
            {
                return true;
            }

            else if (a_dealer.CalcScore() > g_maxScore && a_player.CalcScore() <= g_maxScore)
            {
                return false;
            }

            else if (a_dealer.CalcScore() <= g_maxScore && a_player.CalcScore() <= g_maxScore && a_dealer.CalcScore() > a_player.CalcScore())
            {
                return true;
            }

            else
            {
                return false;
            }
            
        }
    }
}
