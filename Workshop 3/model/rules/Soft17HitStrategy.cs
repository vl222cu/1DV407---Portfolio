using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model.rules
{
    class Soft17HitStrategy : IHitStrategy
    {
        private const int g_hitLimit = 17;
        private model.Player a_dealer;

        public bool DoHit(model.Player a_dealer)
        {
            this.a_dealer = a_dealer;
            return ValidateSoft17();
        }

        public bool ValidateSoft17()
        {
            int[] cardScores = new int[(int)model.Card.Value.Count] { 2, 3, 4, 5, 6, 7, 8, 9, 10, 10, 10, 10, 11 };
            int score = 0;

            int acesAmount = 0;

            foreach (Card c in this.a_dealer.GetHand())
            {
                if (c.GetValue() == Card.Value.Ace)
                {
                    acesAmount++;
                }

                if (c.GetValue() != Card.Value.Hidden)
                {
                    score += cardScores[(int)c.GetValue()];
                }
            }

            if (score > 21 || score == g_hitLimit)
            {
                foreach (Card c in a_dealer.GetHand())
                {
                    if (c.GetValue() == Card.Value.Ace && acesAmount > 0)
                    {
                        score -= 10;
                        acesAmount--;
                    }
                }
            }

            return score < g_hitLimit;
        }
    }
}