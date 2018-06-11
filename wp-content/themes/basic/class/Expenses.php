<?php
/**
 * Created by Adrian Jelonek
 * Date: 10.06.18
 * Time: 11:30
 */

/*Here you have simple CRUD class which process expenses management*/

class Expenses
{
    private $id;
    private $userId;
    private $userName;
    private $expenseType;
    private $expenseValue;
    private $expenseDate;

    public function __construct($currentUserId, $currentUserName, $userNewExpense, $userExpenseValue, $userExpenseDate)
    {
        $this->id = -1;
        $this->userId = $currentUserId;
        $this->userName = $currentUserName;
        $this->expenseType = $userNewExpense;
        $this->expenseValue = $userExpenseValue;
        $this->expenseDate = $userExpenseDate;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id == -1) {
            $stmt = $conn->prepare(
                'INSERT INTO expenses (user_id, user_name, expense_type, expense_value, expense_date) 
                          VALUES (:user_id, :user_name, :expense_type, :expense_value, :expense_date)');
            $result = $stmt->execute
            ([
                    'user_id' => $this->userId,
                    'user_name' => $this->userName,
                    'expense_type' => $this->expenseType,
                    'expense_value' => $this->expenseValue,
                    'expense_date' => $this->expenseDate
                ]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }

    public static function loadAllExpensesNames(PDO $conn, $userId)
    {
        $expensesArr = [];
        $sql = "SELECT expense_type FROM expenses WHERE user_id = $userId";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $expenseNameArr) {
                foreach ($expenseNameArr as $key => $expenseName) {
                    if ($key == 'expense_name') {
                        $expensesArr[] = $expenseName;
                    }
                }
            }
        }
        return $expensesArr;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @param mixed $expenseType
     */
    public function setExpenseType($expenseType)
    {
        $this->expenseType = $expenseType;
    }

    /**
     * @param mixed $expenseValue
     */
    public function setExpenseValue(float $expenseValue)
    {
        $this->expenseValue = $expenseValue;
    }

    /**
     * @param mixed $expenseDate
     */
    public function setExpenseDate($expenseDate)
    {
        $this->expenseDate = $expenseDate;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getExpenseType()
    {
        return $this->expenseType;
    }

    /**
     * @return mixed
     */
    public function getExpenseValue()
    {
        return $this->expenseValue;
    }

    /**
     * @return mixed
     */
    public function getExpenseDate()
    {
        return $this->expenseDate;
    }

}