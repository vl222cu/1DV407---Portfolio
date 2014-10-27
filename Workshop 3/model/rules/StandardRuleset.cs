using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model.rules
{
    class StandardRuleset
    {
        public model.Player a_player;
        public model.Dealer a_dealer;
        public int g_maxScore;

        public StandardRuleset(model.Player a_player, model.Dealer a_dealer, int g_maxScore)
        {
            this.a_player = a_player;
            this.a_dealer = a_dealer;
            this.g_maxScore = g_maxScore;
        }

        public bool isDealerWinner(Player a_player)
        {

            if (a_player.CalcScore() > this.g_maxScore)
            {
                return true;
            }
            else if (this.a_dealer.CalcScore() > this.g_maxScore)
            {
                return false;
            }
            return this.a_dealer.CalcScore() >= a_player.CalcScore();
        }

    }
}
