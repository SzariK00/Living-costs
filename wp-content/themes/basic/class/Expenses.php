<?php
/**
 * Created by Adrian Jelonek
 * Date: 10.06.18
 * Time: 11:30
 */

/*Simple CRUD class which process expenses management*/

class Expenses
{
    private $id;
    private $userId;
    private $userName;
    private $expenseType;
    private $expenseValue;
    private $expenseDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = 0;
        $this->userName = '';
        $this->expenseType = '';
        $this->expenseValue = 0;
        $this->expenseDate = '';
    }

    /*Adding expense to data base.*/
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

    /*Loading expenses names from db*/
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
        $uniqueArr = array_unique($expensesArr, SORT_STRING);
        return $uniqueArr;
    }

    /*Loading expenses from db and filtering them*/
    public static function loadAllExpenses(PDO $conn, $userId, $expenseName, $valueMin, $valueMax, $startDate, $endDate)
    {
        $valueMinHelper = $valueMin;
        $valueMaxHelper = $valueMax;
        $startDateHelper = $startDate;
        $endDateHelper = $endDate;

        if ($valueMin == '') {
            $minValFromDb = "SELECT MIN(expense_value) FROM expenses WHERE user_id = $userId";
            $minValResult = $conn->query($minValFromDb);
            $minValArr = $minValResult->fetchAll();
            $valueMinHelper = $minValArr[0][0];
        }

        if ($valueMax == '') {
            $maxValFromDb = "SELECT MAX(expense_value) FROM expenses WHERE user_id = $userId";
            $maxValResult = $conn->query($maxValFromDb);
            $maxValArr = $maxValResult->fetchAll();
            $valueMaxHelper = $maxValArr[0][0];
        }

        if ($startDate == '') {
            $startDateFromDb = "SELECT MIN(expense_date) FROM expenses WHERE user_id = $userId";
            $startDateResult = $conn->query($startDateFromDb);
            $startDateArr = $startDateResult->fetchAll();
            $startDateHelper = $startDateArr[0][0];
        }

        if ($endDate == '') {
            $endDateFromDb = "SELECT MAX(expense_date) FROM expenses WHERE user_id = $userId";
            $endDateResult = $conn->query($endDateFromDb);
            $endDateArr = $endDateResult->fetchAll();
            $endDateHelper = $endDateArr[0][0];
        }

        if (empty($expenseName)) {

            $stmt = $conn->prepare("SELECT * FROM expenses WHERE 
                                      user_id = :userId AND 
                                      expense_value BETWEEN :valueMin AND :valueMax AND 
                                      expense_date BETWEEN :startDate AND :endDate
                                      ORDER BY expense_date, id");
            $stmt->execute
            ([
                'userId' => $userId,
                'valueMin' => $valueMinHelper,
                'valueMax' => $valueMaxHelper,
                'startDate' => $startDateHelper,
                'endDate' => $endDateHelper
            ]);

            $result = $stmt->fetchAll();

        } else {

            $stmt = $conn->prepare("SELECT * FROM expenses WHERE 
                                      expense_type = :expenseName AND 
                                      user_id = :userId AND 
                                      expense_value BETWEEN :valueMin AND :valueMax AND 
                                      expense_date BETWEEN :startDate AND :endDate
                                      ORDER BY expense_date, id");
            $stmt->execute
            ([
                'expenseName' => $expenseName,
                'userId' => $userId,
                'valueMin' => $valueMinHelper,
                'valueMax' => $valueMaxHelper,
                'startDate' => $startDateHelper,
                'endDate' => $endDateHelper
            ]);

            $result = $stmt->fetchAll();
        }
        return $result;
    }

    /*Deleting expenses from db*/
    public static function deleteExpense(PDO $conn, $expenseId, $userId)
    {
        $stmt = $conn->prepare('DELETE FROM expenses WHERE user_id = :userId AND ID = :id');
        $result = $stmt->execute(['userId' => $userId, 'id' => $expenseId]);
        if (!$result) {
            return false;
        }
        return true;
    }

    /*Retrieving expenses from db by year (Column chart)*/
    public static function retrieveByYear(PDO $conn, $userId, $expenseName)
    {
        if (!empty($expenseName)) {
            $stmt = $conn->prepare("SELECT YEAR(expense_date), SUM(expense_value) 
                FROM expenses 
                WHERE user_id = :userId 
                AND expense_type = :expenseName 
                GROUP BY YEAR(expense_date) ASC");
            $stmt->execute(['userId' => $userId, 'expenseName' => $expenseName]);
            $result = $stmt->fetchAll();

            $arr = [];

            foreach ($result as $key) {
                $newArr =
                    [
                        "label" => $key['YEAR(expense_date)'],
                        "y" => $key['SUM(expense_value)']
                    ];
                $arr[] = $newArr;
            }

            return $arr;

        } else {

            $stmt = $conn->prepare("SELECT YEAR(expense_date), SUM(expense_value) 
                FROM expenses 
                WHERE user_id = :userId 
                GROUP BY YEAR(expense_date) ASC");
            $stmt->execute(['userId' => $userId]);
            $result = $stmt->fetchAll();

            $arr = [];

            foreach ($result as $key) {
                $newArr =
                    [
                        "label" => $key['YEAR(expense_date)'],
                        "y" => $key['SUM(expense_value)']
                    ];
                $arr[] = $newArr;
            }
            return $arr;
        }
    }

    /*Retrieving expenses from db by month (Point chart)*/
    public static function retrieveByMonth(PDO $conn, $userId, $expenseName)
    {
        if (!empty($expenseName)) {
            $stmt = $conn->prepare("SELECT YEAR(expense_date), MONTH(expense_date), SUM(expense_value) 
                FROM expenses 
                WHERE user_id = :userId
                AND expense_type = :expenseName 
                GROUP BY YEAR(expense_date), MONTH(expense_date) ASC");
            $stmt->execute(['userId' => $userId, 'expenseName' => $expenseName]);
            $result = $stmt->fetchAll();

            $arr = [];

            foreach ($result as $key) {
                $newArr =
                    [
                        "label" => $key['MONTH(expense_date)'] . "_" . $key['YEAR(expense_date)'],
                        "y" => $key['SUM(expense_value)']
                    ];
                $arr[] = $newArr;
            }

            return $arr;

        } else {

            $stmt = $conn->prepare("SELECT YEAR(expense_date), MONTH(expense_date), SUM(expense_value) 
                FROM expenses 
                WHERE user_id = :userId
                GROUP BY YEAR(expense_date), MONTH(expense_date) ASC");
            $stmt->execute(['userId' => $userId]);
            $result = $stmt->fetchAll();

            $arr = [];

            foreach ($result as $key) {
                $newArr =
                    [
                        "label" => $key['MONTH(expense_date)'] . "_" . $key['YEAR(expense_date)'],
                        "y" => $key['SUM(expense_value)']
                    ];
                $arr[] = $newArr;
            }
            return $arr;
        }
    }

    /*Selecting expenses from array (Pie Chart)*/
    public static function retrieveShares($arr)
    {
        $expenseTypes = [];
        $resultArr = [];

        /*Creating associative array with unique keys and values*/
        foreach ($arr as $expenseEntry) {
            $expenseType = $expenseEntry['expense_type'];
            $expenseTypes[$expenseType] += $expenseEntry['expense_value'];
        }

        /*Creating 2D array with expenses names and their values*/
        foreach ($expenseTypes as $expenseType => $sum) {
            $arrHelper =
                [
                    'label' => $expenseType,
                    'y' => $sum
                ];
            $resultArr[] = $arrHelper;
        }
        return $resultArr;
    }

    /**
     * @param mixed $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param mixed $userName
     * @return $this
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @param mixed $expenseType
     * @return $this
     */
    public function setExpenseType($expenseType)
    {
        $this->expenseType = $expenseType;
        return $this;
    }

    /**
     * @param mixed $expenseValue
     * @return $this
     */
    public function setExpenseValue($expenseValue)
    {
        $this->expenseValue = $expenseValue;
        return $this;
    }

    /**
     * @param mixed $expenseDate
     * @return $this
     */
    public function setExpenseDate($expenseDate)
    {
        $this->expenseDate = $expenseDate;
        return $this;
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